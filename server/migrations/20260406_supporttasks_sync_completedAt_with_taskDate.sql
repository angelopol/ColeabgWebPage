/*
  Migration: sincronizar completedAt con la fecha de registro de la tarea.
  Objetivo:
  - Para tareas finalizadas, completedAt debe quedar en la misma fecha de taskDate.
  - Si taskDate es NULL, se usa la fecha de createdAt como respaldo.

  Recomendado:
  1) Ejecutar primero el bloque de previsualizacion.
  2) Ejecutar en ventana de mantenimiento.
*/

SET NOCOUNT ON;

BEGIN TRANSACTION;

-- Previsualizacion de filas a actualizar
SELECT
  id,
  title,
  status,
  taskDate,
  createdAt,
  completedAt,
  CAST(COALESCE(taskDate, CAST(createdAt AS DATE)) AS DATETIME2) AS targetCompletedAt
FROM dbo.SupportTasks
WHERE status = 'finalizada'
  AND (
    completedAt IS NULL
    OR CAST(completedAt AS DATE) <> COALESCE(taskDate, CAST(createdAt AS DATE))
  )
ORDER BY id;

-- Actualizacion
UPDATE dbo.SupportTasks
SET
  completedAt = CAST(COALESCE(taskDate, CAST(createdAt AS DATE)) AS DATETIME2),
  updatedAt = SYSDATETIME()
WHERE status = 'finalizada'
  AND (
    completedAt IS NULL
    OR CAST(completedAt AS DATE) <> COALESCE(taskDate, CAST(createdAt AS DATE))
  );

SELECT @@ROWCOUNT AS updatedRows;

COMMIT TRANSACTION;
