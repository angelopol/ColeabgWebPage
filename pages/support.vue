<script setup lang="ts">
type SupportTaskStatus = 'pendiente' | 'finalizada'
type TaskFilterStatus = 'all' | SupportTaskStatus

interface SupportTask {
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

interface SupportReport {
  generatedAt: string
  fromDate: string
  toDate: string
  deParte: string
  para: string
  tasks: SupportTask[]
}

const gate = reactive({
  checking: true,
  granted: false,
  password: '',
  pending: false,
  error: ''
})

const tasks = ref<SupportTask[]>([])
const loadingTasks = ref(false)
const createPending = ref(false)
const updatePending = ref(false)
const reportPending = ref(false)
const importPending = ref(false)

const actionMessage = ref('')
const actionError = ref('')

const today = new Date()
const defaultTo = today.toISOString().slice(0, 10)
const defaultFrom = new Date(today.getTime() - 1000 * 60 * 60 * 24 * 7)
  .toISOString()
  .slice(0, 10)

const toDisplayDate = (value: string | null | undefined) => {
  if (!value) {
    return ''
  }

  if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
    const [year, month, day] = value.split('-')
    return `${day}/${month}/${year}`
  }

  const date = new Date(value)
  if (Number.isNaN(date.getTime())) {
    return ''
  }

  const day = String(date.getDate()).padStart(2, '0')
  const month = String(date.getMonth() + 1).padStart(2, '0')
  const year = String(date.getFullYear())
  return `${day}/${month}/${year}`
}

const parseDisplayDateToIso = (value: string) => {
  const trimmed = value.trim()

  if (!trimmed) {
    return ''
  }

  if (/^\d{4}-\d{2}-\d{2}$/.test(trimmed)) {
    return trimmed
  }

  const match = trimmed.match(/^(\d{2})\/(\d{2})\/(\d{4})$/)
  if (!match) {
    return null
  }

  const [, day, month, year] = match
  const dayNum = Number(day)
  const monthNum = Number(month)
  const yearNum = Number(year)
  const parsedDate = new Date(yearNum, monthNum - 1, dayNum)

  if (Number.isNaN(parsedDate.getTime())) {
    return null
  }

  if (
    parsedDate.getFullYear() !== yearNum ||
    parsedDate.getMonth() + 1 !== monthNum ||
    parsedDate.getDate() !== dayNum
  ) {
    return null
  }

  return `${year}-${month}-${day}`
}

const filters = reactive<{
  status: TaskFilterStatus
  fromDate: string
  toDate: string
  keyword: string
}>({
  status: 'all',
  fromDate: '',
  toDate: '',
  keyword: ''
})

const createForm = reactive({
  title: '',
  description: '',
  module: '',
  taskDate: toDisplayDate(defaultTo),
  markAsCompleted: false
})

const editForm = reactive<{
  id: number | null
  title: string
  description: string
  keywords: string
  module: string
  taskDate: string
  status: SupportTaskStatus
}>({
  id: null,
  title: '',
  description: '',
  keywords: '',
  module: '',
  taskDate: '',
  status: 'pendiente'
})

const reportForm = reactive({
  fromDate: defaultFrom,
  toDate: defaultTo,
  deParte: 'Equipo de Soporte e Informatica',
  para: 'Junta Directiva'
})

const bulkForm = reactive({
  entriesText: '',
  module: ''
})

const reportResult = ref<SupportReport | null>(null)

const ui = reactive({
  showCreateModal: false,
  showFilterModal: false,
  showReportModal: false,
  showEditModal: false,
  showBulkImportModal: false
})

const activeTaskTab = ref<SupportTaskStatus>('pendiente')

const errorStatusCode = (error: unknown) => {
  const parsed = error as {
    statusCode?: number
    data?: {
      statusCode?: number
    }
  }

  return Number(parsed.statusCode || parsed.data?.statusCode || 0)
}

const parseErrorMessage = (error: unknown, fallback: string) => {
  return (
    (error as { data?: { statusMessage?: string } })?.data?.statusMessage || fallback
  )
}

const formatDate = (value: string | null | undefined) => {
  if (!value) {
    return '-'
  }

  if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
    const [year, month, day] = value.split('-')
    return `${day}/${month}/${year}`
  }

  const date = new Date(value)
  if (Number.isNaN(date.getTime())) {
    return value
  }

  return date.toLocaleDateString('es-VE')
}

const formatDateTime = (value: string | null | undefined) => {
  if (!value) {
    return '-'
  }

  const date = new Date(value)
  if (Number.isNaN(date.getTime())) {
    return value
  }

  return date.toLocaleString('es-VE', {
    dateStyle: 'short',
    timeStyle: 'short'
  })
}

const escapeHtml = (value: string) => {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;')
}

const buildReportHtml = (report: SupportReport) => {
  const rows = report.tasks
    .map(
      (task) =>
        `<tr>
          <td>${escapeHtml(task.title)}</td>
          <td>${escapeHtml(task.module || '-')}</td>
          <td>${escapeHtml(formatDate(task.completedAt))}</td>
        </tr>`
    )
    .join('')

  return `<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Reporte de soporte</title>
  <style>
    body { font-family: Arial, sans-serif; color: #111827; margin: 30px; }
    h1 { margin: 0 0 6px; font-size: 20px; text-transform: uppercase; }
    h2 { margin: 0 0 20px; font-size: 14px; color: #374151; }
    .meta { margin-bottom: 14px; font-size: 13px; }
    .meta p { margin: 4px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 12px; }
    th, td { border: 1px solid #d1d5db; padding: 8px; vertical-align: top; text-align: left; }
    th { background: #f3f4f6; }
    .footer { margin-top: 30px; font-size: 12px; }
    .signs { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; margin-top: 36px; }
    .line { border-top: 1px solid #9ca3af; padding-top: 6px; }
  </style>
</head>
<body>
  <h1>Colegio de Abogados del Estado Carabobo</h1>
  <h2>Reporte de actividades finalizadas del equipo de soporte</h2>

  <div class="meta">
    <p><strong>Rango:</strong> ${escapeHtml(formatDate(report.fromDate))} al ${escapeHtml(formatDate(report.toDate))}</p>
    <p><strong>De parte:</strong> ${escapeHtml(report.deParte)}</p>
    <p><strong>Para:</strong> ${escapeHtml(report.para)}</p>
    <p><strong>Generado:</strong> ${escapeHtml(formatDateTime(report.generatedAt))}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Actividad</th>
        <th>Modulo</th>
        <th>Fecha finalizada</th>
      </tr>
    </thead>
    <tbody>
      ${rows || '<tr><td colspan="3">No se registraron actividades finalizadas en el rango indicado.</td></tr>'}
    </tbody>
  </table>

  <div class="footer">
    <p>Documento generado desde el modulo interno de soporte.</p>
    <p>Saludos,<br>${escapeHtml(report.deParte)}</p>
  </div>
</body>
</html>`
}

const handleSupportAuthError = (error: unknown) => {
  if (errorStatusCode(error) !== 401) {
    return false
  }

  ui.showCreateModal = false
  ui.showFilterModal = false
  ui.showReportModal = false
  ui.showEditModal = false
  ui.showBulkImportModal = false
  gate.granted = false
  gate.password = ''
  gate.error = 'Tu sesion de soporte vencio. Vuelve a ingresar la clave.'
  tasks.value = []
  return true
}

const buildTaskQuery = () => {
  return {
    status: filters.status !== 'all' ? filters.status : undefined,
    fromDate: filters.fromDate || undefined,
    toDate: filters.toDate || undefined,
    keyword: filters.keyword.trim() || undefined
  }
}

const resetCreateForm = () => {
  createForm.title = ''
  createForm.description = ''
  createForm.module = ''
  createForm.taskDate = toDisplayDate(defaultTo)
  createForm.markAsCompleted = false
}

const cancelEdit = () => {
  ui.showEditModal = false
  editForm.id = null
  editForm.title = ''
  editForm.description = ''
  editForm.keywords = ''
  editForm.module = ''
  editForm.taskDate = ''
  editForm.status = 'pendiente'
}

const loadTasks = async () => {
  loadingTasks.value = true

  try {
    const response = await $fetch<{ tasks: SupportTask[] }>('/api/support/tasks', {
      query: buildTaskQuery()
    })
    tasks.value = response.tasks
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudieron cargar las tareas.')
  } finally {
    loadingTasks.value = false
  }
}

const checkSupportSession = async () => {
  gate.checking = true

  try {
    const response = await $fetch<{ granted: boolean }>('/api/support/status')
    gate.granted = Boolean(response.granted)

    if (gate.granted) {
      await loadTasks()
    }
  } catch {
    gate.granted = false
  } finally {
    gate.checking = false
  }
}

const unlockSupport = async () => {
  gate.pending = true
  gate.error = ''
  actionMessage.value = ''
  actionError.value = ''

  try {
    await $fetch('/api/support/access', {
      method: 'POST',
      body: {
        password: gate.password
      }
    })

    gate.granted = true
    gate.password = ''
    await loadTasks()
    actionMessage.value = 'Acceso al modulo de soporte habilitado.'
  } catch (error: unknown) {
    gate.error = parseErrorMessage(error, 'No fue posible validar la clave de soporte.')
  } finally {
    gate.pending = false
  }
}

const createTask = async () => {
  createPending.value = true
  actionMessage.value = ''
  actionError.value = ''

  const normalizedTaskDate = parseDisplayDateToIso(createForm.taskDate)

  if (createForm.taskDate.trim() && !normalizedTaskDate) {
    createPending.value = false
    actionError.value = 'La fecha de la actividad debe tener formato DD/MM/YEAR.'
    return
  }

  try {
    await $fetch('/api/support/tasks', {
      method: 'POST',
      body: {
        title: createForm.title,
        description: createForm.description.trim() || undefined,
        module: createForm.module.trim() || undefined,
        taskDate: normalizedTaskDate || undefined,
        status: createForm.markAsCompleted ? 'finalizada' : 'pendiente'
      }
    })

    resetCreateForm()
    ui.showCreateModal = false
    await loadTasks()
    actionMessage.value = 'Tarea creada correctamente.'
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudo crear la tarea.')
  } finally {
    createPending.value = false
  }
}

const startEdit = (task: SupportTask) => {
  ui.showEditModal = true
  editForm.id = task.id
  editForm.title = task.title
  editForm.description = task.description || ''
  editForm.keywords = task.keywords || ''
  editForm.module = task.module || ''
  editForm.taskDate = task.taskDate || ''
  editForm.status = task.status
}

const updateTask = async () => {
  if (!editForm.id) {
    return
  }

  updatePending.value = true
  actionMessage.value = ''
  actionError.value = ''

  try {
    await $fetch(`/api/support/tasks/${editForm.id}`, {
      method: 'PATCH',
      body: {
        title: editForm.title,
        description: editForm.description,
        keywords: editForm.keywords,
        module: editForm.module,
        taskDate: editForm.taskDate,
        status: editForm.status
      }
    })

    await loadTasks()
    cancelEdit()
    actionMessage.value = 'Tarea actualizada correctamente.'
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudo actualizar la tarea.')
  } finally {
    updatePending.value = false
  }
}

const toggleStatus = async (task: SupportTask) => {
  actionMessage.value = ''
  actionError.value = ''

  const nextStatus: SupportTaskStatus =
    task.status === 'pendiente' ? 'finalizada' : 'pendiente'

  try {
    await $fetch(`/api/support/tasks/${task.id}`, {
      method: 'PATCH',
      body: {
        status: nextStatus
      }
    })

    await loadTasks()
    actionMessage.value =
      nextStatus === 'finalizada'
        ? 'Tarea marcada como finalizada.'
        : 'Tarea reabierta como pendiente.'
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudo actualizar el estatus.')
  }
}

const applyFilters = async () => {
  ui.showFilterModal = false
  actionMessage.value = ''
  actionError.value = ''
  await loadTasks()
}

const clearFilters = async () => {
  ui.showFilterModal = false
  filters.status = 'all'
  filters.fromDate = ''
  filters.toDate = ''
  filters.keyword = ''
  await loadTasks()
}

const importFinalizedFromList = async () => {
  importPending.value = true
  actionMessage.value = ''
  actionError.value = ''

  try {
    const response = await $fetch<{
      ok: true
      importedCount: number
    }>('/api/support/tasks/import', {
      method: 'POST',
      body: {
        entriesText: bulkForm.entriesText,
        module: bulkForm.module.trim() || undefined
      }
    })

    ui.showBulkImportModal = false
    bulkForm.entriesText = ''
    bulkForm.module = ''
    await loadTasks()
    actionMessage.value = `${response.importedCount} actividades finalizadas importadas correctamente.`
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudo importar el listado de actividades.')
  } finally {
    importPending.value = false
  }
}

const generateReport = async () => {
  reportPending.value = true
  actionMessage.value = ''
  actionError.value = ''

  try {
    const response = await $fetch<{ report: SupportReport }>('/api/support/report', {
      method: 'POST',
      body: {
        fromDate: reportForm.fromDate,
        toDate: reportForm.toDate,
        deParte: reportForm.deParte,
        para: reportForm.para
      }
    })

    reportResult.value = response.report
    ui.showReportModal = false
    actionMessage.value = 'Reporte generado correctamente.'
  } catch (error: unknown) {
    if (handleSupportAuthError(error)) {
      return
    }

    actionError.value = parseErrorMessage(error, 'No se pudo generar el reporte.')
  } finally {
    reportPending.value = false
  }
}

const printReport = () => {
  if (!reportResult.value || typeof window === 'undefined') {
    return
  }

  const printWindow = window.open('', '_blank', 'width=1024,height=768')
  if (!printWindow) {
    actionError.value = 'El navegador bloqueo la ventana de impresion.'
    return
  }

  const html = buildReportHtml(reportResult.value)
  printWindow.document.open()
  printWindow.document.write(html)
  printWindow.document.close()
  printWindow.focus()
  printWindow.print()
}

const downloadReportDoc = () => {
  if (!reportResult.value || typeof window === 'undefined') {
    return
  }

  const html = buildReportHtml(reportResult.value)
  const blob = new Blob(['\uFEFF', html], { type: 'application/msword' })
  const url = window.URL.createObjectURL(blob)
  const link = window.document.createElement('a')
  link.href = url
  link.download = `reporte-actividades-${reportResult.value.fromDate}-${reportResult.value.toDate}.doc`
  window.document.body.appendChild(link)
  link.click()
  link.remove()
  window.URL.revokeObjectURL(url)
}

const sanitizePdfText = (value: string) =>
  value
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^\x20-\x7E]/g, '')

const escapePdfText = (value: string) =>
  sanitizePdfText(value)
    .replace(/\\/g, '\\\\')
    .replace(/\(/g, '\\(')
    .replace(/\)/g, '\\)')

const wrapPdfLine = (line: string, maxChars = 92) => {
  if (line.length <= maxChars) {
    return [line]
  }

  const words = line.split(/\s+/)
  const wrapped: string[] = []
  let current = ''

  for (const word of words) {
    const candidate = current ? `${current} ${word}` : word
    if (candidate.length <= maxChars) {
      current = candidate
      continue
    }

    if (current) {
      wrapped.push(current)
    }
    current = word
  }

  if (current) {
    wrapped.push(current)
  }

  return wrapped.length ? wrapped : [line]
}

const buildReportPdfBytes = (report: SupportReport) => {
  const baseLines = [
    'Colegio de Abogados del Estado Carabobo',
    'Reporte de actividades finalizadas del equipo de soporte',
    `Rango: ${formatDate(report.fromDate)} al ${formatDate(report.toDate)}`,
    `De parte: ${report.deParte}`,
    `Para: ${report.para}`,
    `Generado: ${formatDateTime(report.generatedAt)}`,
    '',
    'Actividades:'
  ]

  if (!report.tasks.length) {
    baseLines.push('- No se registraron actividades finalizadas en el rango indicado.')
  } else {
    for (const activity of report.tasks) {
      baseLines.push(
        `- ${activity.title} | Modulo: ${activity.module || '-'} | Fecha finalizada: ${formatDate(activity.completedAt)}`
      )
    }
  }

  baseLines.push('', 'Saludos,', report.deParte)

  const wrappedLines = baseLines.flatMap((line) => wrapPdfLine(line))
  const maxLines = 55
  const finalLines =
    wrappedLines.length > maxLines
      ? [...wrappedLines.slice(0, maxLines - 1), '... Se omitieron actividades adicionales por espacio.']
      : wrappedLines

  const streamLines = ['BT', '/F1 10 Tf', '44 806 Td', '13 TL']
  finalLines.forEach((line, index) => {
    streamLines.push(`(${escapePdfText(line)}) Tj`)
    if (index < finalLines.length - 1) {
      streamLines.push('T*')
    }
  })
  streamLines.push('ET')

  const contentStream = streamLines.join('\n')

  const objects = [
    '',
    '<< /Type /Catalog /Pages 2 0 R >>',
    '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
    '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
    '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
    `<< /Length ${contentStream.length} >>\nstream\n${contentStream}\nendstream`
  ]

  let pdf = '%PDF-1.4\n'
  const offsets: number[] = [0]

  for (let i = 1; i < objects.length; i += 1) {
    offsets[i] = pdf.length
    pdf += `${i} 0 obj\n${objects[i]}\nendobj\n`
  }

  const xrefOffset = pdf.length
  pdf += `xref\n0 ${objects.length}\n`
  pdf += '0000000000 65535 f \n'

  for (let i = 1; i < objects.length; i += 1) {
    pdf += `${String(offsets[i]).padStart(10, '0')} 00000 n \n`
  }

  pdf += `trailer\n<< /Size ${objects.length} /Root 1 0 R >>\nstartxref\n${xrefOffset}\n%%EOF`

  return new TextEncoder().encode(pdf)
}

const exportAndSharePdf = async () => {
  if (!reportResult.value || typeof window === 'undefined') {
    return
  }

  actionMessage.value = ''
  actionError.value = ''

  try {
    const bytes = buildReportPdfBytes(reportResult.value)
    const blob = new Blob([bytes], { type: 'application/pdf' })
    const fileName = `reporte-actividades-${reportResult.value.fromDate}-${reportResult.value.toDate}.pdf`
    const file = new File([blob], fileName, { type: 'application/pdf' })
    const nav = window.navigator as Navigator & {
      canShare?: (data?: ShareData) => boolean
    }

    if (typeof nav.share === 'function' && nav.canShare?.({ files: [file] })) {
      await nav.share({
        title: 'Reporte de actividades',
        text: 'Compartiendo reporte de actividades finalizadas.',
        files: [file]
      })

      actionMessage.value = 'PDF exportado y compartido correctamente.'
      return
    }

    const url = window.URL.createObjectURL(blob)
    const link = window.document.createElement('a')
    link.href = url
    link.download = fileName
    window.document.body.appendChild(link)
    link.click()
    link.remove()
    window.URL.revokeObjectURL(url)

    actionMessage.value = 'PDF exportado. Puedes compartirlo desde tu dispositivo.'
  } catch {
    actionError.value = 'No se pudo exportar o compartir el PDF.'
  }
}

const pendingTasks = computed(() => tasks.value.filter((task) => task.status === 'pendiente'))
const completedTasks = computed(() =>
  tasks.value.filter((task) => task.status === 'finalizada')
)
const visibleTasks = computed(() =>
  activeTaskTab.value === 'pendiente' ? pendingTasks.value : completedTasks.value
)

const activeFilterSummary = computed(() => {
  const chunks: string[] = []

  if (filters.status !== 'all') {
    chunks.push(`Estatus: ${filters.status}`)
  }

  if (filters.fromDate || filters.toDate) {
    chunks.push(`Fecha: ${filters.fromDate || '...'} - ${filters.toDate || '...'}`)
  }

  if (filters.keyword.trim()) {
    chunks.push(`Keyword: ${filters.keyword.trim()}`)
  }

  return chunks.length ? chunks.join(' | ') : 'Sin filtros activos'
})

onMounted(async () => {
  await checkSupportSession()
})
</script>

<template>
  <section class="space-y-5">
    <div v-if="gate.checking" class="panel">
      <h1 class="text-3xl text-white">Modulo de Soporte</h1>
      <p class="mt-3 text-sm text-slate-200">Validando acceso al equipo de soporte...</p>
    </div>

    <div v-else-if="!gate.granted" class="panel mx-auto max-w-2xl">
      <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Acceso Protegido</p>
      <h1 class="mt-2 text-4xl text-white">Clave del Equipo de Soporte</h1>
      <p class="mt-3 text-sm text-slate-200">
        Esta area requiere una clave de acceso para continuar.
      </p>
      <p class="mt-1 text-xs text-slate-300">
        Al validar la clave se guarda una cookie de acceso por 90 dias.
      </p>

      <form class="mt-6 space-y-4" @submit.prevent="unlockSupport">
        <input
          v-model="gate.password"
          type="password"
          class="field"
          placeholder="Clave de soporte"
          required
        />
        <button class="btn-primary w-full" :disabled="gate.pending" type="submit">
          {{ gate.pending ? 'Validando...' : 'Ingresar al modulo' }}
        </button>
      </form>

      <p
        v-if="gate.error"
        class="mt-4 rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
      >
        {{ gate.error }}
      </p>
    </div>

    <template v-else>

      <div class="panel space-y-4">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
          <div>
            <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Soporte e Informatica</p>
            <h1 class="mt-2 text-3xl text-white sm:text-4xl">Gestor Interno de Tareas</h1>
            <p class="mt-2 text-sm text-slate-200">
              Vista compacta para movil con acciones por modal.
            </p>
          </div>
          <button class="btn-primary w-full sm:w-auto" type="button" :disabled="loadingTasks" @click="loadTasks">
            {{ loadingTasks ? 'Actualizando...' : 'Actualizar tareas' }}
          </button>
        </div>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap">
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="ui.showCreateModal = true">
            Nueva tarea
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="ui.showBulkImportModal = true">
            Carga finalizadas
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="ui.showFilterModal = true">
            Filtros
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="ui.showReportModal = true">
            Generar reporte
          </button>
          <button class="btn-secondary !w-full !px-3 !py-2 text-xs sm:!w-auto sm:text-sm" type="button" @click="loadTasks">
            Refrescar
          </button>
        </div>

        <div class="flex flex-wrap gap-2 text-xs">
          <span class="rounded-full border border-vino-200/40 bg-vino-700/35 px-3 py-1 text-vino-50">
            Pendientes: {{ pendingTasks.length }}
          </span>
          <span class="rounded-full border border-verde-200/40 bg-verde-700/35 px-3 py-1 text-verde-50">
            Finalizadas: {{ completedTasks.length }}
          </span>
          <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-slate-100">
            {{ activeFilterSummary }}
          </span>
        </div>
      </div>

      <p
        v-if="actionMessage"
        class="rounded-xl border border-emerald-300/30 bg-emerald-300/10 px-3 py-2 text-sm text-emerald-100"
      >
        {{ actionMessage }}
      </p>

      <p
        v-if="actionError"
        class="rounded-xl border border-rose-300/30 bg-rose-300/10 px-3 py-2 text-sm text-rose-100"
      >
        {{ actionError }}
      </p>

      <div class="panel">
        <div class="flex items-center justify-between gap-3">
          <h2 class="text-2xl text-white">Tareas</h2>
          <div class="inline-flex rounded-xl border border-white/20 bg-black/20 p-1">
            <button
              type="button"
              class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
              :class="activeTaskTab === 'pendiente' ? 'bg-vino-500 text-white' : 'text-slate-200 hover:bg-white/10'"
              @click="activeTaskTab = 'pendiente'"
            >
              Pendientes
            </button>
            <button
              type="button"
              class="rounded-lg px-3 py-1.5 text-xs font-medium transition"
              :class="activeTaskTab === 'finalizada' ? 'bg-verde-500 text-white' : 'text-slate-200 hover:bg-white/10'"
              @click="activeTaskTab = 'finalizada'"
            >
              Finalizadas
            </button>
          </div>
        </div>

        <div class="mt-4 max-h-[68vh] space-y-3 overflow-y-auto pr-1">
          <article v-for="task in visibleTasks" :key="`task-${task.id}`" class="rounded-2xl border border-white/15 bg-white/10 p-3 sm:p-4">
            <details>
              <summary class="flex cursor-pointer list-none items-start justify-between gap-2">
                <div class="min-w-0">
                  <p class="truncate text-sm font-semibold text-sand-100 sm:text-base">{{ task.title }}</p>
                  <p class="mt-1 text-xs text-slate-300">
                    {{ task.module || 'Sin modulo' }} · Fecha: {{ formatDate(task.taskDate) }}
                  </p>
                </div>
                <span
                  class="shrink-0 rounded-full px-2 py-1 text-[10px] font-semibold uppercase"
                  :class="task.status === 'pendiente' ? 'bg-vino-500/30 text-vino-100' : 'bg-verde-500/30 text-verde-100'"
                >
                  {{ task.status }}
                </span>
              </summary>

              <div class="mt-3 space-y-3">
                <p v-if="task.description" class="text-sm text-slate-200">{{ task.description }}</p>

                <div class="flex flex-wrap gap-2 text-xs text-slate-200">
                  <span class="rounded-md bg-black/25 px-2 py-1">Keywords: {{ task.keywords || '-' }}</span>
                  <span class="rounded-md bg-black/25 px-2 py-1">Creada: {{ formatDateTime(task.createdAt) }}</span>
                  <span class="rounded-md bg-black/25 px-2 py-1">Actualizada: {{ formatDateTime(task.updatedAt) }}</span>
                  <span class="rounded-md bg-black/25 px-2 py-1">Finalizada: {{ formatDateTime(task.completedAt) }}</span>
                </div>

                <div class="grid gap-2 sm:grid-cols-2">
                  <button
                    type="button"
                    :class="task.status === 'pendiente' ? 'btn-primary w-full !py-2 text-xs sm:text-sm' : 'btn-secondary w-full !py-2 text-xs sm:text-sm'"
                    @click="toggleStatus(task)"
                  >
                    {{ task.status === 'pendiente' ? 'Marcar finalizada' : 'Reabrir tarea' }}
                  </button>
                  <button class="btn-secondary w-full !py-2 text-xs sm:text-sm" type="button" @click="startEdit(task)">
                    Editar tarea
                  </button>
                </div>
              </div>
            </details>
          </article>

          <p v-if="!visibleTasks.length" class="text-sm text-slate-300">
            No hay tareas en esta vista con los filtros actuales.
          </p>
        </div>
      </div>

      <details v-if="reportResult" class="panel">
        <summary class="cursor-pointer list-none text-lg font-semibold text-sand-100">
          Vista previa del reporte de actividades
        </summary>

        <div class="mt-4 space-y-4">
          <div class="space-y-1 text-sm text-slate-200">
            <p><strong>Rango:</strong> {{ formatDate(reportResult.fromDate) }} al {{ formatDate(reportResult.toDate) }}</p>
            <p><strong>De parte:</strong> {{ reportResult.deParte }}</p>
            <p><strong>Para:</strong> {{ reportResult.para }}</p>
            <p><strong>Generado:</strong> {{ formatDateTime(reportResult.generatedAt) }}</p>
          </div>

          <div class="flex flex-wrap gap-2">
            <button class="btn-secondary !px-3 !py-2 text-xs sm:text-sm" type="button" @click="printReport">
              Imprimir
            </button>
            <button class="btn-secondary !px-3 !py-2 text-xs sm:text-sm" type="button" @click="exportAndSharePdf">
              Exportar/Compartir PDF
            </button>
            <button class="btn-primary !px-3 !py-2 text-xs sm:text-sm" type="button" @click="downloadReportDoc">
              Descargar .doc
            </button>
          </div>

          <div class="max-h-72 overflow-y-auto rounded-xl border border-white/15 bg-black/20">
            <table class="min-w-full text-left text-sm text-slate-100">
              <thead class="bg-black/35 text-xs uppercase tracking-wide text-slate-300">
                <tr>
                  <th class="px-3 py-2">Actividad</th>
                  <th class="px-3 py-2">Modulo</th>
                  <th class="px-3 py-2">Fecha finalizada</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="task in reportResult.tasks"
                  :key="`report-${task.id}`"
                  class="border-t border-white/10"
                >
                  <td class="px-3 py-2">{{ task.title }}</td>
                  <td class="px-3 py-2">{{ task.module || '-' }}</td>
                  <td class="px-3 py-2">{{ formatDate(task.completedAt) }}</td>
                </tr>
                <tr v-if="!reportResult.tasks.length">
                  <td class="px-3 py-3 text-slate-300" colspan="3">
                    No se registraron actividades finalizadas en este rango.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </details>

      <div
        v-if="ui.showCreateModal"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showCreateModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-2xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl text-white">Nueva tarea</h2>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showCreateModal = false">Cerrar</button>
          </div>

          <form class="mt-4 space-y-3" @submit.prevent="createTask">
            <input
              v-model="createForm.title"
              type="text"
              class="field"
              placeholder="Titulo de la tarea"
              minlength="3"
              required
            />

            <div class="grid gap-3 sm:grid-cols-2">
              <input v-model="createForm.module" type="text" class="field" placeholder="Modulo (opcional)" />
              <input
                v-model="createForm.taskDate"
                type="text"
                inputmode="numeric"
                class="field"
                placeholder="DD/MM/YEAR"
                maxlength="10"
                pattern="\d{2}/\d{2}/\d{4}"
              />
            </div>

            <textarea
              v-model="createForm.description"
              class="field min-h-28"
              placeholder="Descripcion de la actividad (opcional)"
            />

            <label class="flex items-center gap-2 rounded-xl border border-white/15 bg-white/5 px-3 py-2 text-sm text-slate-100">
              <input
                v-model="createForm.markAsCompleted"
                type="checkbox"
                class="h-4 w-4 rounded border-white/30 bg-white/10"
              />
              Registrar actividad como finalizada
            </label>

            <button class="btn-primary w-full" :disabled="createPending" type="submit">
              {{ createPending ? 'Creando...' : 'Guardar actividad' }}
            </button>
          </form>
        </div>
      </div>

      <div
        v-if="ui.showFilterModal"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showFilterModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl text-white">Filtros</h2>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showFilterModal = false">Cerrar</button>
          </div>

          <form class="mt-4 space-y-3" @submit.prevent="applyFilters">
            <select v-model="filters.status" class="field">
              <option value="all">Todos los estatus</option>
              <option value="pendiente">Pendiente</option>
              <option value="finalizada">Finalizada</option>
            </select>

            <div class="grid gap-3 sm:grid-cols-2">
              <input v-model="filters.fromDate" type="date" class="field" />
              <input v-model="filters.toDate" type="date" class="field" />
            </div>

            <input
              v-model="filters.keyword"
              type="text"
              class="field"
              placeholder="Buscar por titulo, modulo o keywords"
            />

            <div class="grid gap-2 sm:grid-cols-2">
              <button class="btn-primary w-full" type="submit">Aplicar filtros</button>
              <button class="btn-secondary w-full" type="button" @click="clearFilters">
                Limpiar filtros
              </button>
            </div>
          </form>
        </div>
      </div>

      <div
        v-if="ui.showBulkImportModal"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showBulkImportModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-2xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl text-white">Carga masiva de finalizadas</h2>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showBulkImportModal = false">Cerrar</button>
          </div>

          <p class="mt-3 text-xs text-slate-300">
            Formato por linea: {titulo};{fecha}. Fechas aceptadas: YYYY-MM-DD o DD/MM/YYYY.
          </p>

          <form class="mt-4 space-y-3" @submit.prevent="importFinalizedFromList">
            <input
              v-model="bulkForm.module"
              type="text"
              class="field"
              placeholder="Modulo para todas las lineas (opcional)"
            />

            <textarea
              v-model="bulkForm.entriesText"
              class="field min-h-56"
              placeholder="Renovacion de acceso movil;2026-04-06&#10;Carga de respaldo sistema;06/04/2026"
              required
            />

            <button class="btn-primary w-full" :disabled="importPending" type="submit">
              {{ importPending ? 'Importando...' : 'Importar actividades finalizadas' }}
            </button>
          </form>
        </div>
      </div>

      <div
        v-if="ui.showReportModal"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="ui.showReportModal = false"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-2xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl text-white">Generar reporte</h2>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="ui.showReportModal = false">Cerrar</button>
          </div>

          <form class="mt-4 grid gap-3 md:grid-cols-2" @submit.prevent="generateReport">
            <input v-model="reportForm.fromDate" type="date" class="field" required />
            <input v-model="reportForm.toDate" type="date" class="field" required />
            <input v-model="reportForm.deParte" class="field" placeholder="De parte" required />
            <input v-model="reportForm.para" class="field" placeholder="Para" required />
            <button class="btn-primary md:col-span-2" :disabled="reportPending" type="submit">
              {{ reportPending ? 'Generando...' : 'Generar reporte' }}
            </button>
          </form>
        </div>
      </div>

      <div
        v-if="ui.showEditModal && editForm.id"
        class="fixed inset-0 z-40 flex items-end justify-center bg-black/70 p-0 sm:items-center sm:p-4"
        @click.self="cancelEdit"
      >
        <div class="w-full max-h-[92vh] overflow-y-auto rounded-t-3xl border border-white/20 bg-ink p-5 sm:max-w-2xl sm:rounded-3xl">
          <div class="flex items-center justify-between gap-3">
            <h2 class="text-xl text-white">Editar tarea #{{ editForm.id }}</h2>
            <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="cancelEdit">Cerrar</button>
          </div>

          <form class="mt-4 space-y-3" @submit.prevent="updateTask">
            <input
              v-model="editForm.title"
              type="text"
              class="field"
              placeholder="Titulo"
              minlength="3"
              required
            />

            <div class="grid gap-3 md:grid-cols-3">
              <input v-model="editForm.module" class="field" placeholder="Modulo" />
              <input v-model="editForm.taskDate" type="date" class="field" />
              <select v-model="editForm.status" class="field">
                <option value="pendiente">Pendiente</option>
                <option value="finalizada">Finalizada</option>
              </select>
            </div>

            <input v-model="editForm.keywords" class="field" placeholder="Keywords" />

            <textarea
              v-model="editForm.description"
              class="field min-h-28"
              placeholder="Descripcion"
            />

            <div class="grid gap-2 sm:grid-cols-2">
              <button class="btn-primary w-full" :disabled="updatePending" type="submit">
                {{ updatePending ? 'Guardando...' : 'Guardar cambios' }}
              </button>
              <button class="btn-secondary w-full" type="button" @click="cancelEdit">Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </template>
  </section>
</template>
