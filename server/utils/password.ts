import bcrypt from 'bcryptjs'

export const isBcryptHash = (value: string) => /^\$2[aby]\$/.test(value)

export const hashPassword = async (plain: string) => bcrypt.hash(plain, 10)

export const verifyPassword = async (plain: string, current: string) => {
  if (!current) {
    return false
  }

  if (isBcryptHash(current)) {
    return bcrypt.compare(plain, current)
  }

  return plain === current
}
