import sql from 'mssql'

export type DbRequest = {
  input: (name: string, type: any, value?: any) => DbRequest
  query: <T = any>(query: string) => Promise<{ recordset: T[] }>
}

const globalDb = globalThis as unknown as {
  __cabPool?: any
  __cabPoolPromise?: Promise<any>
}

const buildConfig = () => {
  const config = useRuntimeConfig()

  if (!config.dbServer || !config.dbName || !config.dbUser) {
    throw createError({
      statusCode: 500,
      statusMessage:
        'Faltan variables de base de datos. Configure DB_SERVER, DB_NAME y DB_USER.'
    })
  }

  return {
    user: config.dbUser,
    password: config.dbPass,
    server: config.dbServer,
    port: config.dbPort,
    database: config.dbName,
    pool: {
      max: 20,
      min: 0,
      idleTimeoutMillis: 30000
    },
    options: {
      encrypt: config.dbEncrypt,
      trustServerCertificate: config.dbTrustServerCertificate,
      enableArithAbort: true
    }
  } as any
}

export const getDbPool = async () => {
  if (globalDb.__cabPool?.connected) {
    return globalDb.__cabPool
  }

  if (!globalDb.__cabPoolPromise) {
    globalDb.__cabPoolPromise = new sql.ConnectionPool(buildConfig())
      .connect()
      .then((pool: any) => {
        globalDb.__cabPool = pool
        return pool
      })
      .catch((error: unknown) => {
        globalDb.__cabPoolPromise = undefined
        throw error
      })
  }

  return globalDb.__cabPoolPromise
}

export const dbRequest = async () => {
  const pool = await getDbPool()
  return pool.request() as DbRequest
}

export { sql }
