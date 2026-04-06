import { dbRequest, sql } from '~/server/utils/db'

export type SupportTaskStatus = 'pendiente' | 'finalizada'

export interface SupportTask {
  id: number
  title: string
  description: string | null
  keywords: string | null
  module: string | null
  taskDate: string | null
  status: SupportTaskStatus
  createdAt: string
  updatedAt: string
  completedAt: string | null
}

type SupportTaskRow = {
  id: number
  title: string
  description: string | null
  keywords: string | null
  module: string | null
  taskDate: string | null
  status: string
  createdAt: Date | string
  updatedAt: Date | string
  completedAt: Date | string | null
}

let tableInitialized = false

const toIso = (value: Date | string | null | undefined) => {
  if (!value) {
    return null
  }

  const date = new Date(value)
  if (Number.isNaN(date.getTime())) {
    return null
  }

  return date.toISOString()
}

const mapRow = (row: SupportTaskRow): SupportTask => ({
  id: row.id,
  title: row.title,
  description: row.description,
  keywords: row.keywords,
  module: row.module,
  taskDate: row.taskDate,
  status: row.status === 'finalizada' ? 'finalizada' : 'pendiente',
  createdAt: toIso(row.createdAt) || new Date().toISOString(),
  updatedAt: toIso(row.updatedAt) || new Date().toISOString(),
  completedAt: toIso(row.completedAt)
})

export const ensureSupportTasksTable = async () => {
  if (tableInitialized) {
    return
  }

  const request = await dbRequest()
  await request.query(`
    IF OBJECT_ID('dbo.SupportTasks', 'U') IS NULL
    BEGIN
      CREATE TABLE dbo.SupportTasks (
        id INT IDENTITY(1,1) PRIMARY KEY,
        title NVARCHAR(220) NOT NULL,
        description NVARCHAR(MAX) NULL,
        keywords NVARCHAR(400) NULL,
        module NVARCHAR(120) NULL,
        taskDate DATE NULL,
        status NVARCHAR(20) NOT NULL CONSTRAINT DF_SupportTasks_status DEFAULT('pendiente'),
        createdAt DATETIME2 NOT NULL CONSTRAINT DF_SupportTasks_createdAt DEFAULT(SYSDATETIME()),
        updatedAt DATETIME2 NOT NULL CONSTRAINT DF_SupportTasks_updatedAt DEFAULT(SYSDATETIME()),
        completedAt DATETIME2 NULL
      );

      CREATE INDEX IX_SupportTasks_status ON dbo.SupportTasks(status);
      CREATE INDEX IX_SupportTasks_taskDate ON dbo.SupportTasks(taskDate);
      CREATE INDEX IX_SupportTasks_completedAt ON dbo.SupportTasks(completedAt);
    END
  `)

  tableInitialized = true
}

export const listSupportTasks = async (filters: {
  status?: string
  fromDate?: string
  toDate?: string
  keyword?: string
}) => {
  await ensureSupportTasksTable()

  const request = await dbRequest()
  request.input('status', sql.NVarChar(20), filters.status || null)
  request.input('fromDate', sql.Date, filters.fromDate || null)
  request.input('toDate', sql.Date, filters.toDate || null)
  request.input('keyword', sql.NVarChar(220), filters.keyword ? `%${filters.keyword}%` : null)

  const result = await request.query<SupportTaskRow>(`
    SELECT
      id,
      title,
      description,
      keywords,
      module,
      CONVERT(varchar(10), taskDate, 23) AS taskDate,
      status,
      createdAt,
      updatedAt,
      completedAt
    FROM dbo.SupportTasks
    WHERE
      (@status IS NULL OR status = @status)
      AND (
        @fromDate IS NULL OR
        CAST(COALESCE(taskDate, createdAt) AS DATE) >= @fromDate
      )
      AND (
        @toDate IS NULL OR
        CAST(COALESCE(taskDate, createdAt) AS DATE) <= @toDate
      )
      AND (
        @keyword IS NULL OR
        title LIKE @keyword OR
        ISNULL(description, '') LIKE @keyword OR
        ISNULL(keywords, '') LIKE @keyword OR
        ISNULL(module, '') LIKE @keyword
      )
    ORDER BY
      CASE WHEN status = 'pendiente' THEN 0 ELSE 1 END,
      COALESCE(taskDate, CAST(createdAt AS DATE)) DESC,
      id DESC
  `)

  return result.recordset.map(mapRow)
}

export const createSupportTask = async (payload: {
  title: string
  description?: string
  keywords?: string
  module?: string
  taskDate?: string
}) => {
  await ensureSupportTasksTable()

  const request = await dbRequest()
  request.input('title', sql.NVarChar(220), payload.title.trim())
  request.input('description', sql.NVarChar(sql.MAX), payload.description?.trim() || null)
  request.input('keywords', sql.NVarChar(400), payload.keywords?.trim() || null)
  request.input('module', sql.NVarChar(120), payload.module?.trim() || null)
  request.input('taskDate', sql.Date, payload.taskDate || null)

  const result = await request.query<SupportTaskRow>(`
    INSERT INTO dbo.SupportTasks (title, description, keywords, module, taskDate, status)
    OUTPUT
      inserted.id,
      inserted.title,
      inserted.description,
      inserted.keywords,
      inserted.module,
      CONVERT(varchar(10), inserted.taskDate, 23) AS taskDate,
      inserted.status,
      inserted.createdAt,
      inserted.updatedAt,
      inserted.completedAt
    VALUES (@title, @description, @keywords, @module, @taskDate, 'pendiente')
  `)

  return mapRow(result.recordset[0])
}

export const updateSupportTask = async (
  id: number,
  payload: {
    title?: string
    description?: string | null
    keywords?: string | null
    module?: string | null
    taskDate?: string | null
    status?: SupportTaskStatus
  }
) => {
  await ensureSupportTasksTable()

  const request = await dbRequest()
  request.input('id', sql.Int, id)

  const fields: string[] = []

  if (typeof payload.title === 'string') {
    request.input('title', sql.NVarChar(220), payload.title.trim())
    fields.push('title = @title')
  }

  if (payload.description !== undefined) {
    request.input('description', sql.NVarChar(sql.MAX), payload.description?.trim() || null)
    fields.push('description = @description')
  }

  if (payload.keywords !== undefined) {
    request.input('keywords', sql.NVarChar(400), payload.keywords?.trim() || null)
    fields.push('keywords = @keywords')
  }

  if (payload.module !== undefined) {
    request.input('module', sql.NVarChar(120), payload.module?.trim() || null)
    fields.push('module = @module')
  }

  if (payload.taskDate !== undefined) {
    request.input('taskDate', sql.Date, payload.taskDate || null)
    fields.push('taskDate = @taskDate')
  }

  if (payload.status !== undefined) {
    request.input('status', sql.NVarChar(20), payload.status)
    fields.push('status = @status')
    fields.push("completedAt = CASE WHEN @status = 'finalizada' THEN SYSDATETIME() ELSE NULL END")
  }

  if (!fields.length) {
    throw createError({
      statusCode: 400,
      statusMessage: 'No hay cambios para actualizar la tarea.'
    })
  }

  const result = await request.query<SupportTaskRow>(`
    UPDATE dbo.SupportTasks
    SET
      ${fields.join(', ')},
      updatedAt = SYSDATETIME()
    OUTPUT
      inserted.id,
      inserted.title,
      inserted.description,
      inserted.keywords,
      inserted.module,
      CONVERT(varchar(10), inserted.taskDate, 23) AS taskDate,
      inserted.status,
      inserted.createdAt,
      inserted.updatedAt,
      inserted.completedAt
    WHERE id = @id
  `)

  if (!result.recordset[0]) {
    throw createError({
      statusCode: 404,
      statusMessage: 'No se encontro la tarea indicada.'
    })
  }

  return mapRow(result.recordset[0])
}

export const listCompletedTasksForReport = async (
  fromDate: string,
  toDate: string,
  keyword?: string
) => {
  await ensureSupportTasksTable()

  const request = await dbRequest()
  request.input('fromDate', sql.Date, fromDate)
  request.input('toDate', sql.Date, toDate)
  request.input('keyword', sql.NVarChar(220), keyword ? `%${keyword}%` : null)

  const result = await request.query<SupportTaskRow>(`
    SELECT
      id,
      title,
      description,
      keywords,
      module,
      CONVERT(varchar(10), taskDate, 23) AS taskDate,
      status,
      createdAt,
      updatedAt,
      completedAt
    FROM dbo.SupportTasks
    WHERE
      status = 'finalizada'
      AND completedAt IS NOT NULL
      AND CAST(completedAt AS DATE) >= @fromDate
      AND CAST(completedAt AS DATE) <= @toDate
      AND (
        @keyword IS NULL OR
        title LIKE @keyword OR
        ISNULL(description, '') LIKE @keyword OR
        ISNULL(keywords, '') LIKE @keyword OR
        ISNULL(module, '') LIKE @keyword
      )
    ORDER BY completedAt ASC, id ASC
  `)

  return result.recordset.map(mapRow)
}
