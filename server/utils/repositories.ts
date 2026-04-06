import type {
  AdminOverview,
  InscriptionRecord,
  LawyerRecord,
  OperationRecord,
  SolvencyRecord
} from '~/types/domain'
import { nowInCaracas } from './date'
import type { DbRequest } from './db'
import { dbRequest, sql } from './db'

interface UserRow {
  email: string
  pass: string | null
  Clase: string | null
  CodClie: string | null
}

const likePattern = (value: string) => `%${value}%`

const scalarCount = async (
  query: string,
  binder?: (request: DbRequest) => DbRequest
) => {
  const request = await dbRequest()
  const bound = binder ? binder(request) : request
  const result = await bound.query<{ c: number }>(query)
  return Number(result.recordset[0]?.c ?? 0)
}

export const findUserByEmail = async (email: string) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)

  const result = await request.query<UserRow>(
    'SELECT TOP 1 email, pass, Clase, CodClie FROM USUARIOS WHERE email = @email'
  )

  return result.recordset[0] ?? null
}

export const findWorkerByEmail = async (email: string) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)

  const result = await request.query<{ email: string }>(
    'SELECT TOP 1 email FROM USUARIOS WHERE email = @email AND pass IS NULL AND Clase IS NULL AND CodClie IS NULL'
  )

  return result.recordset[0] ?? null
}

export const emailExists = async (email: string) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)

  const result = await request.query<{ found: number }>(
    'SELECT TOP 1 1 AS found FROM USUARIOS WHERE email = @email'
  )

  return Boolean(result.recordset[0]?.found)
}

export const codClieExistsInUsers = async (codClie: string) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), likePattern(codClie))

  const result = await request.query<{ found: number }>(
    'SELECT TOP 1 1 AS found FROM USUARIOS WHERE CodClie LIKE @codClie'
  )

  return Boolean(result.recordset[0]?.found)
}

export const claseExistsInUsers = async (clase: string) => {
  const request = await dbRequest()
  request.input('clase', sql.VarChar(80), likePattern(clase))

  const result = await request.query<{ found: number }>(
    'SELECT TOP 1 1 AS found FROM USUARIOS WHERE Clase LIKE @clase'
  )

  return Boolean(result.recordset[0]?.found)
}

export const createUser = async (
  email: string,
  passwordHash: string,
  clase: string,
  codClie: string
) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)
  request.input('pass', sql.VarChar(255), passwordHash)
  request.input('clase', sql.VarChar(50), clase)
  request.input('codClie', sql.VarChar(50), codClie)

  await request.query(
    'INSERT INTO USUARIOS (email, pass, Clase, CodClie) VALUES (@email, @pass, @clase, @codClie)'
  )
}

export const updateUserPasswordByEmail = async (
  email: string,
  passwordHash: string
) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)
  request.input('pass', sql.VarChar(255), passwordHash)

  await request.query('UPDATE USUARIOS SET pass = @pass WHERE email = @email')
}

export const updateUserEmail = async (currentEmail: string, nextEmail: string) => {
  const request = await dbRequest()
  request.input('currentEmail', sql.VarChar(255), currentEmail)
  request.input('nextEmail', sql.VarChar(255), nextEmail)

  await request.query(
    'UPDATE USUARIOS SET email = @nextEmail WHERE email = @currentEmail'
  )
}

export const deleteUserByEmail = async (email: string) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)

  await request.query('DELETE FROM USUARIOS WHERE email = @email')
}

export const findLawyerByCedulaOrId3 = async (needle: string) => {
  const request = await dbRequest()
  request.input('needle', sql.VarChar(80), likePattern(needle))

  const result = await request.query<LawyerRecord>(
    'SELECT TOP 1 CodClie, Clase, Descrip, ID3 FROM SACLIE WHERE CodClie LIKE @needle OR ID3 LIKE @needle'
  )

  return result.recordset[0] ?? null
}

export const findLawyerByInpre = async (inpre: string) => {
  const requestExact = await dbRequest()
  requestExact.input('claseExact', sql.VarChar(50), inpre)

  const exact = await requestExact.query<LawyerRecord>(
    'SELECT TOP 1 CodClie, Clase, Descrip, ID3 FROM SACLIE WHERE Clase = @claseExact'
  )

  if (exact.recordset[0]) {
    return exact.recordset[0]
  }

  const requestLike = await dbRequest()
  requestLike.input('claseLike', sql.VarChar(80), likePattern(inpre))

  const like = await requestLike.query<LawyerRecord>(
    'SELECT TOP 1 CodClie, Clase, Descrip, ID3 FROM SACLIE WHERE Clase LIKE @claseLike'
  )

  return like.recordset[0] ?? null
}

export const findLawyersByNeedle = async (needle: string) => {
  const request = await dbRequest()
  request.input('needle', sql.VarChar(80), likePattern(needle))

  const result = await request.query<LawyerRecord>(
    `SELECT TOP 30 CodClie, Clase, Descrip, ID3
       FROM SACLIE
      WHERE CodClie LIKE @needle OR ID3 LIKE @needle OR Clase LIKE @needle
      ORDER BY Descrip ASC`
  )

  return result.recordset
}

export const getInscriptionData = async (codClie: string) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), likePattern(codClie))

  const result = await request.query<InscriptionRecord>(
    'SELECT TOP 1 CodClie, Fecha, Numero, Folio FROM SACLIE_08 WHERE CodClie LIKE @codClie'
  )

  return result.recordset[0] ?? null
}

export const getActiveSolvency = async (codClie: string) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), likePattern(codClie))

  const result = await request.query<SolvencyRecord>(
    'SELECT TOP 1 CodClie, hasta, Status, NumeroD, CarnetNum2, FechaE, Solved FROM SOLV WHERE CodClie LIKE @codClie AND Status = 1 ORDER BY hasta DESC'
  )

  return result.recordset[0] ?? null
}

export const operationsByClient = async (codClieOrId3: string, year?: number) => {
  const request = await dbRequest()
  request.input('needle', sql.VarChar(80), likePattern(codClieOrId3))

  if (typeof year === 'number') {
    request.input('year', sql.VarChar(10), `%${year}%`)
    const result = await request.query<OperationRecord>(
      'SELECT CodClie, ID3, FechaE, NumeroD, OrdenC, Notas1, Notas2, Notas3, Notas4, Notas5, Notas6, Notas7 FROM SAFACT WHERE (CodClie LIKE @needle OR ID3 LIKE @needle) AND FechaE LIKE @year ORDER BY FechaE DESC'
    )

    return result.recordset
  }

  const result = await request.query<OperationRecord>(
    'SELECT CodClie, ID3, FechaE, NumeroD, OrdenC, Notas1, Notas2, Notas3, Notas4, Notas5, Notas6, Notas7 FROM SAFACT WHERE CodClie LIKE @needle OR ID3 LIKE @needle ORDER BY FechaE DESC'
  )

  return result.recordset
}

export const allOperationsSAFACT = async (codClieOrId3: string) => {
  return operationsByClient(codClieOrId3)
}

export const allOperationsSAACXC = async (codClie: string) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), likePattern(codClie))

  const result = await request.query<OperationRecord>(
    'SELECT CodClie, FechaE, Document, Notas1, Notas2, Notas3, Notas4, Notas5, Notas6, Notas7 FROM SAACXC WHERE CodClie LIKE @codClie ORDER BY FechaE DESC'
  )

  return result.recordset
}

export const hasHistoricOperations = async (codClieOrId3: string) => {
  const request = await dbRequest()
  request.input('needle', sql.VarChar(80), likePattern(codClieOrId3))

  const result = await request.query<{ found: number }>(
    'SELECT TOP 1 1 AS found FROM SAFACT WHERE CodClie LIKE @needle OR ID3 LIKE @needle'
  )

  return Boolean(result.recordset[0]?.found)
}

export const operationByNumeroD = async (numeroD: string) => {
  const request = await dbRequest()
  request.input('numeroD', sql.VarChar(40), numeroD)

  const result = await request.query<OperationRecord>(
    'SELECT TOP 1 FechaE, NumeroD, OrdenC, Notas1, Notas2, Notas3, Notas4, Notas5, Notas6, Notas7 FROM SAFACT WHERE NumeroD = @numeroD'
  )

  return result.recordset[0] ?? null
}

export const workerEntryExists = async (email: string, date: string) => {
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)
  request.input('fecha', sql.VarChar(30), date)

  const result = await request.query<{ c: number }>(
    'SELECT COUNT(1) AS c FROM ASSIST WHERE email = @email AND fecha = @fecha'
  )

  return Number(result.recordset[0]?.c ?? 0) > 0
}

export const markWorkerEntry = async (email: string) => {
  const now = nowInCaracas()
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)
  request.input('fechaEntrada', sql.VarChar(30), now.datetime)
  request.input('fecha', sql.VarChar(30), now.date)

  await request.query(
    'INSERT INTO ASSIST (email, fecha_entrada, fecha) VALUES (@email, @fechaEntrada, @fecha)'
  )

  return now
}

export const markWorkerExit = async (email: string) => {
  const now = nowInCaracas()
  const request = await dbRequest()
  request.input('email', sql.VarChar(255), email)
  request.input('fechaSalida', sql.VarChar(30), now.datetime)
  request.input('fecha', sql.VarChar(30), now.date)

  await request.query(
    'UPDATE ASSIST SET fecha_salida = @fechaSalida WHERE email = @email AND fecha = @fecha'
  )

  return now
}

export const assistsInRange = async (fromDate: string, toDate: string) => {
  const request = await dbRequest()
  request.input('fromDate', sql.VarChar(30), fromDate)
  request.input('toDate', sql.VarChar(30), toDate)

  const result = await request.query<{
    email: string
    fecha: string
    fecha_entrada?: string
    fecha_salida?: string
  }>(
    'SELECT email, fecha, fecha_entrada, fecha_salida FROM ASSIST WHERE fecha >= @fromDate AND fecha <= @toDate ORDER BY fecha, email'
  )

  return result.recordset
}

export const createSolvency = async (payload: {
  codClie: string
  hasta: string
  numeroD: string
  carnetNum2?: string | null
}) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), payload.codClie)
  request.input('hasta', sql.VarChar(40), payload.hasta)
  request.input('numeroD', sql.VarChar(40), payload.numeroD)
  request.input('carnetNum2', sql.VarChar(30), payload.carnetNum2 ?? null)

  await request.query(
    'INSERT INTO SOLV (CodClie, hasta, Status, NumeroD, CarnetNum2) VALUES (@codClie, @hasta, 1, @numeroD, @carnetNum2)'
  )
}

export const hasActiveSolvencyByCodClie = async (codClie: string) => {
  const request = await dbRequest()
  request.input('codClie', sql.VarChar(80), likePattern(codClie))

  const result = await request.query<{ found: number }>(
    'SELECT TOP 1 1 AS found FROM SOLV WHERE CodClie LIKE @codClie AND Status = 1'
  )

  return Boolean(result.recordset[0]?.found)
}

export const hasActiveCarnet = async (carnetNum2: string) => {
  const request = await dbRequest()
  request.input('carnetNum2', sql.VarChar(30), carnetNum2)

  const result = await request.query<{ found: number }>(
    "SELECT TOP 1 1 AS found FROM SOLV WHERE CarnetNum2 = @carnetNum2 AND Status = 1"
  )

  return Boolean(result.recordset[0]?.found)
}

export const markNumeroDSolved = async (numeroD: string) => {
  const request = await dbRequest()
  request.input('numeroD', sql.VarChar(40), numeroD)

  await request.query('UPDATE SOLV SET Solved = 1 WHERE NumeroD = @numeroD')
}

export const adminOverview = async (): Promise<AdminOverview> => {
  const now = nowInCaracas()

  const [pendingFailed, statusTwo, duplicatesCodClie, duplicatesNumeroD, duplicatesCarnet, assistsToday] =
    await Promise.all([
      scalarCount(
        'SELECT COUNT(*) AS c FROM SOLV WHERE (Status = 0 OR Status = 3) AND (Solved = 0 OR Solved IS NULL)'
      ),
      scalarCount(
        'SELECT COUNT(*) AS c FROM SOLV WHERE Status = 2 AND (Solved = 0 OR Solved IS NULL)'
      ),
      scalarCount(
        'SELECT COUNT(*) AS c FROM (SELECT CodClie FROM SOLV WHERE Status = 1 GROUP BY CodClie HAVING COUNT(CodClie) > 1) AS T'
      ),
      scalarCount(
        'SELECT COUNT(*) AS c FROM (SELECT NumeroD FROM SOLV GROUP BY NumeroD HAVING COUNT(NumeroD) > 1) AS T'
      ),
      scalarCount(
        "SELECT COUNT(*) AS c FROM (SELECT CarnetNum2 FROM SOLV WHERE Status = 1 AND CarnetNum2 IS NOT NULL AND CarnetNum2 <> 'None' GROUP BY CarnetNum2 HAVING COUNT(CarnetNum2) > 1) AS T"
      ),
      scalarCount(
        'SELECT COUNT(*) AS c FROM ASSIST WHERE fecha = @today',
        (request) => request.input('today', sql.VarChar(30), now.date)
      )
    ])

  return {
    pendingFailed,
    statusTwo,
    duplicatesCodClie,
    duplicatesNumeroD,
    duplicatesCarnet,
    assistsToday
  }
}

export const listPendingFailed = async (limit = 120) => {
  const top = Math.min(Math.max(limit, 1), 300)
  const request = await dbRequest()

  const result = await request.query<SolvencyRecord>(
    `SELECT TOP (${top}) CodClie, FechaE, NumeroD, Solved, CarnetNum2, hasta, Status
       FROM SOLV
      WHERE (Status = 0 OR Status = 3) AND (Solved = 0 OR Solved IS NULL)
      ORDER BY NumeroD DESC`
  )

  return result.recordset
}

export const listStatusTwo = async (limit = 120) => {
  const top = Math.min(Math.max(limit, 1), 300)
  const request = await dbRequest()

  const result = await request.query<SolvencyRecord>(
    `SELECT TOP (${top}) CodClie, FechaE, NumeroD, Solved, CarnetNum2, hasta, Status
       FROM SOLV
      WHERE Status = 2 AND (Solved = 0 OR Solved IS NULL)
      ORDER BY NumeroD DESC`
  )

  return result.recordset
}

export const listDuplicateCodClie = async () => {
  const request = await dbRequest()

  const result = await request.query<{ CodClie: string; total: number }>(
    'SELECT CodClie, COUNT(CodClie) AS total FROM SOLV WHERE Status = 1 GROUP BY CodClie HAVING COUNT(CodClie) > 1'
  )

  return result.recordset
}

export const listDuplicateNumeroD = async () => {
  const request = await dbRequest()

  const result = await request.query<{ NumeroD: string; total: number }>(
    'SELECT NumeroD, COUNT(NumeroD) AS total FROM SOLV GROUP BY NumeroD HAVING COUNT(NumeroD) > 1'
  )

  return result.recordset
}

export const listDuplicateCarnet = async () => {
  const request = await dbRequest()

  const result = await request.query<{ CarnetNum2: string; total: number }>(
    "SELECT CarnetNum2, COUNT(CarnetNum2) AS total FROM SOLV WHERE Status = 1 AND CarnetNum2 != 'None' GROUP BY CarnetNum2 HAVING COUNT(CarnetNum2) > 1"
  )

  return result.recordset
}
