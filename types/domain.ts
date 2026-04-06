export type AppRole = 'member' | 'worker' | 'admin'

export interface UserSession {
  email: string
  role: AppRole
  codClie?: string | null
  clase?: string | null
  displayName?: string | null
}

export interface LawyerRecord {
  CodClie: string
  Clase: string
  Descrip?: string | null
  ID3?: string | null
}

export interface InscriptionRecord {
  CodClie: string
  Fecha?: string | null
  Numero?: string | null
  Folio?: string | null
}

export interface SolvencyRecord {
  CodClie: string
  hasta?: string | null
  Status?: number | null
  NumeroD?: string | null
  CarnetNum2?: string | null
  FechaE?: string | null
  Solved?: number | null
}

export interface OperationRecord {
  CodClie?: string | null
  ID3?: string | null
  FechaE?: string | null
  NumeroD?: string | null
  OrdenC?: string | null
  Document?: string | null
  Notas1?: string | null
  Notas2?: string | null
  Notas3?: string | null
  Notas4?: string | null
  Notas5?: string | null
  Notas6?: string | null
  Notas7?: string | null
}

export interface AdminOverview {
  pendingFailed: number
  statusTwo: number
  duplicatesCodClie: number
  duplicatesNumeroD: number
  duplicatesCarnet: number
  assistsToday: number
}
