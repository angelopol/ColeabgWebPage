import nodemailer from 'nodemailer'

export const sendEmail = async (to: string, subject: string, html: string) => {
  const config = useRuntimeConfig()

  if (!config.smtpHost || !config.smtpUser) {
    return { sent: false, reason: 'smtp_not_configured' as const }
  }

  const transport = nodemailer.createTransport({
    host: config.smtpHost,
    port: config.smtpPort,
    secure: config.smtpSecure,
    auth: {
      user: config.smtpUser,
      pass: config.smtpPass
    }
  })

  await transport.sendMail({
    from: config.smtpUser,
    to,
    subject,
    html,
    text: html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim()
  })

  return { sent: true as const }
}
