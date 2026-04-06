declare module 'nuxt/config' {
  export const defineNuxtConfig: (config: any) => any
}

declare module 'tailwindcss' {
  export interface Config {
    [key: string]: any
  }
}

declare module 'mssql' {
  export interface IResult<T = any> {
    recordset: T[]
  }

  export class Request {
    input(name: string, type: any, value?: any): this
    query<T = any>(query: string): Promise<IResult<T>>
  }

  export class ConnectionPool {
    connected: boolean
    constructor(config: any)
    connect(): Promise<ConnectionPool>
    request(): Request
  }

  const sql: {
    Request: typeof Request
    ConnectionPool: typeof ConnectionPool
    VarChar: any
    [key: string]: any
  }
  export default sql
}

declare module 'nodemailer' {
  const nodemailer: any
  export default nodemailer
}

declare module 'zod' {
  export const z: any
}

declare module 'h3' {
  export type H3Event = any
}

declare module 'vue' {
  export const computed: any
  export const reactive: any
  export const ref: any
  export const watch: any
  export const watchEffect: any
  export const onMounted: any
  export const onBeforeUnmount: any
  export type DefineComponent<T = any, U = any, V = any> = any
}

declare module 'node:crypto' {
  export const createHmac: any
  export const timingSafeEqual: any
}

declare const process: {
  env: Record<string, string | undefined>
}

declare const Buffer: any

declare function useRuntimeConfig(): any
declare function createError(input: any): any
declare function defineEventHandler<T = any>(
  handler: (event: any) => T | Promise<T>
): any
declare function readBody<T = any>(event: any): Promise<T>
declare function getQuery(event: any): Record<string, any>
declare function setCookie(event: any, name: string, value: string, options?: any): void
declare function deleteCookie(event: any, name: string, options?: any): void
declare function getCookie(event: any, name: string): string | undefined
declare function definePageMeta(meta: any): void
declare function useAsyncData<T = any>(...args: any[]): Promise<any>
declare function navigateTo(to: any): Promise<any> | any
declare function useRoute(): any
declare function useState<T = any>(key: string, init?: () => T): { value: T }
declare function computed<T = any>(getter: () => T): { value: T }
declare function reactive<T extends object>(value: T): T
declare function ref<T = any>(value?: T): { value: T }
declare function watch(source: any, callback: any, options?: any): void
declare function watchEffect(callback: any): void
declare function onMounted(callback: any): void
declare function onBeforeUnmount(callback: any): void
declare function useAuth(): any
declare function defineNuxtPlugin(plugin: any): any
declare function defineNuxtRouteMiddleware(handler: any): any

declare const $fetch: <T = any>(request: any, options?: any) => Promise<T>
