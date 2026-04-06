import type { OperationRecord } from '~/types/domain'

export const operationNotes = (row: OperationRecord) => {
  return [
    row.Notas1,
    row.Notas2,
    row.Notas3,
    row.Notas4,
    row.Notas5,
    row.Notas6,
    row.Notas7
  ]
    .filter(Boolean)
    .join(' ')
    .trim()
}

export const detectRole = (email: string) => {
  const normalized = email.toLowerCase().trim()
  if (normalized.endsWith('@coleabg.local') || normalized.includes('admin')) {
    return 'admin' as const
  }

  return 'member' as const
}
