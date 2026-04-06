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
  keyword: string | null
  deParte: string
  para: string
  totalTasks: number
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

const actionMessage = ref('')
const actionError = ref('')

const today = new Date()
const defaultTo = today.toISOString().slice(0, 10)
const defaultFrom = new Date(today.getTime() - 1000 * 60 * 60 * 24 * 7)
  .toISOString()
  .slice(0, 10)

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
  keywords: '',
  module: '',
  taskDate: defaultTo
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
  keyword: '',
  deParte: 'Equipo de Soporte e Informatica',
  para: 'Junta Directiva'
})

const reportResult = ref<SupportReport | null>(null)

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
      (task, index) =>
        `<tr>
          <td>${index + 1}</td>
          <td>${escapeHtml(task.title)}</td>
          <td>${escapeHtml(task.module || '-')}</td>
          <td>${escapeHtml(task.keywords || '-')}</td>
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
  <h2>Reporte de tareas finalizadas del equipo de soporte</h2>

  <div class="meta">
    <p><strong>Rango:</strong> ${escapeHtml(formatDate(report.fromDate))} al ${escapeHtml(formatDate(report.toDate))}</p>
    <p><strong>De parte:</strong> ${escapeHtml(report.deParte)}</p>
    <p><strong>Para:</strong> ${escapeHtml(report.para)}</p>
    <p><strong>Generado:</strong> ${escapeHtml(formatDateTime(report.generatedAt))}</p>
    <p><strong>Total de tareas finalizadas:</strong> ${report.totalTasks}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Tarea</th>
        <th>Modulo</th>
        <th>Keywords</th>
        <th>Fecha finalizada</th>
      </tr>
    </thead>
    <tbody>
      ${rows || '<tr><td colspan="5">No se registraron tareas finalizadas en el rango indicado.</td></tr>'}
    </tbody>
  </table>

  <div class="footer">
    <p>Documento generado desde el modulo interno de soporte.</p>
  </div>

  <div class="signs">
    <div class="line">De parte: ${escapeHtml(report.deParte)}</div>
    <div class="line">Para: ${escapeHtml(report.para)}</div>
  </div>
</body>
</html>`
}

const handleSupportAuthError = (error: unknown) => {
  if (errorStatusCode(error) !== 401) {
    return false
  }

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
  createForm.keywords = ''
  createForm.module = ''
  createForm.taskDate = defaultTo
}

const cancelEdit = () => {
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

  try {
    await $fetch('/api/support/tasks', {
      method: 'POST',
      body: {
        title: createForm.title,
        description: createForm.description,
        keywords: createForm.keywords,
        module: createForm.module,
        taskDate: createForm.taskDate
      }
    })

    resetCreateForm()
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
  actionMessage.value = ''
  actionError.value = ''
  await loadTasks()
}

const clearFilters = async () => {
  filters.status = 'all'
  filters.fromDate = ''
  filters.toDate = ''
  filters.keyword = ''
  await loadTasks()
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
        keyword: reportForm.keyword,
        deParte: reportForm.deParte,
        para: reportForm.para
      }
    })

    reportResult.value = response.report
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
  link.download = `reporte-soporte-${reportResult.value.fromDate}-${reportResult.value.toDate}.doc`
  window.document.body.appendChild(link)
  link.click()
  link.remove()
  window.URL.revokeObjectURL(url)
}

const pendingTasks = computed(() => tasks.value.filter((task) => task.status === 'pendiente'))
const completedTasks = computed(() =>
  tasks.value.filter((task) => task.status === 'finalizada')
)

onMounted(async () => {
  await checkSupportSession()
})
</script>

<template>
  <section class="space-y-6">
    <div v-if="gate.checking" class="panel">
      <h1 class="text-3xl text-white">Modulo de Soporte</h1>
      <p class="mt-3 text-sm text-slate-200">Validando acceso al equipo de soporte...</p>
    </div>

    <div v-else-if="!gate.granted" class="panel mx-auto max-w-2xl">
      <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Acceso Protegido</p>
      <h1 class="mt-2 text-4xl text-white">Clave del Equipo de Soporte</h1>
      <p class="mt-3 text-sm text-slate-200">
        Esta area requiere la clave SOPORTE_PSSWD configurada en el entorno.
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
      <div class="panel flex flex-wrap items-center justify-between gap-3">
        <div>
          <p class="text-xs uppercase tracking-[0.25em] text-sand-200">Soporte e Informatica</p>
          <h1 class="mt-2 text-4xl text-white">Gestor Interno de Tareas</h1>
          <p class="mt-2 text-sm text-slate-200">
            Control de pendientes/finalizadas, filtros y reporte por rango de fechas.
          </p>
        </div>
        <button class="btn-primary" type="button" :disabled="loadingTasks" @click="loadTasks">
          {{ loadingTasks ? 'Actualizando...' : 'Actualizar tareas' }}
        </button>
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

      <div class="grid gap-6 xl:grid-cols-[1.05fr,0.95fr]">
        <div class="panel space-y-4">
          <h2 class="text-2xl text-white">Registrar nueva tarea</h2>
          <form class="space-y-3" @submit.prevent="createTask">
            <input
              v-model="createForm.title"
              type="text"
              class="field"
              placeholder="Titulo de la tarea"
              minlength="3"
              required
            />

            <div class="grid gap-3 sm:grid-cols-2">
              <input v-model="createForm.module" type="text" class="field" placeholder="Modulo" />
              <input v-model="createForm.taskDate" type="date" class="field" />
            </div>

            <input
              v-model="createForm.keywords"
              type="text"
              class="field"
              placeholder="Keywords (separadas por coma)"
            />

            <textarea
              v-model="createForm.description"
              class="field min-h-28"
              placeholder="Descripcion de la tarea"
            />

            <button class="btn-primary w-full" :disabled="createPending" type="submit">
              {{ createPending ? 'Creando...' : 'Crear tarea' }}
            </button>
          </form>
        </div>

        <div class="panel space-y-4">
          <h2 class="text-2xl text-white">Filtros de consulta</h2>
          <form class="space-y-3" @submit.prevent="applyFilters">
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

      <div v-if="editForm.id" class="panel space-y-4">
        <h2 class="text-2xl text-white">Editar tarea #{{ editForm.id }}</h2>

        <form class="space-y-3" @submit.prevent="updateTask">
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

      <div class="grid gap-6 lg:grid-cols-2">
        <div class="panel">
          <div class="flex items-center justify-between gap-2">
            <h2 class="text-2xl text-white">Pendientes</h2>
            <span class="rounded-lg bg-vino-600/40 px-2 py-1 text-xs text-vino-100">
              {{ pendingTasks.length }}
            </span>
          </div>

          <div class="mt-4 max-h-[30rem] space-y-3 overflow-y-auto pr-1">
            <article
              v-for="task in pendingTasks"
              :key="`pend-${task.id}`"
              class="rounded-2xl border border-white/15 bg-white/10 p-4"
            >
              <div class="flex flex-wrap items-start justify-between gap-2">
                <h3 class="text-base font-semibold text-sand-100">{{ task.title }}</h3>
                <button class="btn-primary !px-3 !py-1.5 text-xs" @click="toggleStatus(task)">
                  Finalizar
                </button>
              </div>

              <p v-if="task.description" class="mt-2 text-sm text-slate-200">{{ task.description }}</p>

              <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-300">
                <span class="rounded-md bg-black/25 px-2 py-1">Modulo: {{ task.module || '-' }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Fecha tarea: {{ formatDate(task.taskDate) }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Keywords: {{ task.keywords || '-' }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Creada: {{ formatDateTime(task.createdAt) }}</span>
              </div>

              <button class="btn-secondary mt-3 !px-3 !py-1.5 text-xs" @click="startEdit(task)">
                Editar
              </button>
            </article>

            <p v-if="!pendingTasks.length" class="text-sm text-slate-300">
              No hay tareas pendientes con los filtros actuales.
            </p>
          </div>
        </div>

        <div class="panel">
          <div class="flex items-center justify-between gap-2">
            <h2 class="text-2xl text-white">Finalizadas</h2>
            <span class="rounded-lg bg-verde-700/40 px-2 py-1 text-xs text-verde-100">
              {{ completedTasks.length }}
            </span>
          </div>

          <div class="mt-4 max-h-[30rem] space-y-3 overflow-y-auto pr-1">
            <article
              v-for="task in completedTasks"
              :key="`fin-${task.id}`"
              class="rounded-2xl border border-white/15 bg-emerald-900/20 p-4"
            >
              <div class="flex flex-wrap items-start justify-between gap-2">
                <h3 class="text-base font-semibold text-emerald-100">{{ task.title }}</h3>
                <button class="btn-secondary !px-3 !py-1.5 text-xs" @click="toggleStatus(task)">
                  Reabrir
                </button>
              </div>

              <p v-if="task.description" class="mt-2 text-sm text-slate-200">{{ task.description }}</p>

              <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-300">
                <span class="rounded-md bg-black/25 px-2 py-1">Modulo: {{ task.module || '-' }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Fecha tarea: {{ formatDate(task.taskDate) }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Keywords: {{ task.keywords || '-' }}</span>
                <span class="rounded-md bg-black/25 px-2 py-1">Finalizada: {{ formatDateTime(task.completedAt) }}</span>
              </div>

              <button class="btn-secondary mt-3 !px-3 !py-1.5 text-xs" @click="startEdit(task)">
                Editar
              </button>
            </article>

            <p v-if="!completedTasks.length" class="text-sm text-slate-300">
              No hay tareas finalizadas con los filtros actuales.
            </p>
          </div>
        </div>
      </div>

      <div class="panel space-y-4">
        <h2 class="text-2xl text-white">Reporte de tareas finalizadas</h2>

        <form class="grid gap-3 md:grid-cols-2" @submit.prevent="generateReport">
          <input v-model="reportForm.fromDate" type="date" class="field" required />
          <input v-model="reportForm.toDate" type="date" class="field" required />
          <input
            v-model="reportForm.deParte"
            class="field"
            placeholder="De parte"
            required
          />
          <input v-model="reportForm.para" class="field" placeholder="Para" required />
          <input
            v-model="reportForm.keyword"
            class="field md:col-span-2"
            placeholder="Filtro de keywords para reporte (opcional)"
          />
          <button class="btn-primary md:col-span-2" :disabled="reportPending" type="submit">
            {{ reportPending ? 'Generando reporte...' : 'Generar reporte' }}
          </button>
        </form>

        <div v-if="reportResult" class="rounded-2xl border border-white/15 bg-white/10 p-4">
          <div class="flex flex-wrap items-center justify-between gap-2">
            <h3 class="text-xl text-sand-100">Vista previa del reporte</h3>
            <div class="flex flex-wrap gap-2">
              <button class="btn-secondary !px-3 !py-1.5 text-xs" type="button" @click="printReport">
                Imprimir
              </button>
              <button class="btn-primary !px-3 !py-1.5 text-xs" type="button" @click="downloadReportDoc">
                Descargar .doc
              </button>
            </div>
          </div>

          <div class="mt-3 space-y-1 text-sm text-slate-200">
            <p><strong>Rango:</strong> {{ formatDate(reportResult.fromDate) }} al {{ formatDate(reportResult.toDate) }}</p>
            <p><strong>De parte:</strong> {{ reportResult.deParte }}</p>
            <p><strong>Para:</strong> {{ reportResult.para }}</p>
            <p><strong>Generado:</strong> {{ formatDateTime(reportResult.generatedAt) }}</p>
            <p><strong>Total finalizadas:</strong> {{ reportResult.totalTasks }}</p>
          </div>

          <div class="mt-4 max-h-72 overflow-y-auto rounded-xl border border-white/15 bg-black/20">
            <table class="min-w-full text-left text-sm text-slate-100">
              <thead class="bg-black/35 text-xs uppercase tracking-wide text-slate-300">
                <tr>
                  <th class="px-3 py-2">#</th>
                  <th class="px-3 py-2">Tarea</th>
                  <th class="px-3 py-2">Modulo</th>
                  <th class="px-3 py-2">Keywords</th>
                  <th class="px-3 py-2">Fecha finalizada</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(task, idx) in reportResult.tasks"
                  :key="`report-${task.id}`"
                  class="border-t border-white/10"
                >
                  <td class="px-3 py-2">{{ idx + 1 }}</td>
                  <td class="px-3 py-2">{{ task.title }}</td>
                  <td class="px-3 py-2">{{ task.module || '-' }}</td>
                  <td class="px-3 py-2">{{ task.keywords || '-' }}</td>
                  <td class="px-3 py-2">{{ formatDate(task.completedAt) }}</td>
                </tr>
                <tr v-if="!reportResult.tasks.length">
                  <td class="px-3 py-3 text-slate-300" colspan="5">
                    No se registraron tareas finalizadas en este rango.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </template>
  </section>
</template>
