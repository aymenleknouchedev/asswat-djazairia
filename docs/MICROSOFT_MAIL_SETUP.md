# Sending mail from contact@asswatdjazairia.com with Microsoft 365

## Why the old setup stopped working

The site used to send over SMTP (`smtp.office365.com`). That stopped with:

```
535 5.7.139 Authentication unsuccessful, SmtpClientAuthentication is disabled for the Tenant
```

Microsoft disables SMTP AUTH (username + password) on tenants by default, and is
retiring it for Exchange Online entirely — basic auth for SMTP client submission
works only until **the end of December 2026**. So the site now sends through the
**Microsoft Graph API** with OAuth2 instead: no password, no expiry date hanging
over it.

Receiving mail on the mailbox is unaffected — this is only about sending.

## What you have to do in Microsoft (once)

You need a Microsoft 365 / Entra ID **Global Administrator** for these steps.

### 1. Register an app

1. Go to <https://entra.microsoft.com> → **Applications** → **App registrations** → **New registration**.
2. Name: `Asswat Djazairia — Website Mail`.
3. Supported account types: **Accounts in this organizational directory only**.
4. Leave the redirect URI empty → **Register**.
5. From the **Overview** page copy:
   - **Application (client) ID** → `MS_GRAPH_CLIENT_ID`
   - **Directory (tenant) ID** → `MS_GRAPH_TENANT_ID`

### 2. Give it permission to send mail

1. In the app → **API permissions** → **Add a permission** → **Microsoft Graph**
   → **Application permissions** → search `Mail.Send` → check it → **Add permissions**.
2. Click **Grant admin consent for <tenant>** and confirm. The status must show a
   green check — without consent every send returns `403`.

> Make sure it is the **Application** permission, not the Delegated one.

### 3. Create a client secret

1. App → **Certificates & secrets** → **Client secrets** → **New client secret**.
2. Description: `website`, expiry: 24 months → **Add**.
3. Copy the **Value** immediately (it is shown only once) → `MS_GRAPH_CLIENT_SECRET`.

**Note the expiry date.** When the secret expires, sending stops until a new one
is generated and put in `.env`.

### 4. (Recommended) Restrict the app to one mailbox

`Mail.Send` as an application permission lets the app send as **any** mailbox in
the tenant. Lock it down to the contact mailbox with an application access policy
in Exchange Online PowerShell:

```powershell
Connect-ExchangeOnline

New-ApplicationAccessPolicy `
  -AppId <MS_GRAPH_CLIENT_ID> `
  -PolicyScopeGroupId contact@asswatdjazairia.com `
  -AccessRight RestrictAccess `
  -Description "Website may only send as the contact mailbox"
```

Allow ~30 minutes for the policy to apply. Verify with:

```powershell
Test-ApplicationAccessPolicy -Identity contact@asswatdjazairia.com -AppId <MS_GRAPH_CLIENT_ID>
```

## What to put in `.env`

```dotenv
MAIL_MAILER=microsoft
MS_GRAPH_TENANT_ID=00000000-0000-0000-0000-000000000000
MS_GRAPH_CLIENT_ID=00000000-0000-0000-0000-000000000000
MS_GRAPH_CLIENT_SECRET=your-secret-value
MS_GRAPH_FROM=contact@asswatdjazairia.com

MAIL_FROM_ADDRESS=contact@asswatdjazairia.com
MAIL_FROM_NAME="أصوات الجزائرية"
ADMIN_EMAIL=contact@asswatdjazairia.com
```

Then clear the cached config:

```bash
php artisan config:clear
```

## Testing

```bash
php artisan mail:test you@example.com
```

Force the mailer explicitly if you want to compare:

```bash
php artisan mail:test you@example.com --mailer=microsoft
php artisan mail:test you@example.com --mailer=resend
```

A successful send lands in the mailbox **and** in the contact mailbox's *Sent Items*
(Graph saves sent mail there automatically).

## Common errors

| Error | Cause |
| --- | --- |
| `Could not get a Microsoft Graph token (HTTP 401): AADSTS7000215` | Wrong or expired client secret. |
| `Could not get a Microsoft Graph token (HTTP 400): AADSTS900023` | Wrong tenant ID. |
| `HTTP 403: Access is denied. Check credentials and try again.` | Admin consent for `Mail.Send` not granted, or the application access policy excludes this mailbox. |
| `HTTP 404: The requested user ... was not found` | `MS_GRAPH_FROM` is not a real mailbox in the tenant. |
| `The message is too large for Microsoft Graph sendMail` | Attachments over the 4 MB request limit; the form caps each file at 2.5 MB. |

## How it works in the code

| File | Role |
| --- | --- |
| [`app/Mail/Transport/MicrosoftGraphTransport.php`](../app/Mail/Transport/MicrosoftGraphTransport.php) | Symfony transport: gets an OAuth token (cached 50 min), POSTs the MIME message to `/users/{mailbox}/sendMail`. |
| [`app/Providers/AppServiceProvider.php`](../app/Providers/AppServiceProvider.php) | Registers the `microsoft` mailer via `Mail::extend()`. |
| [`config/mail.php`](../config/mail.php) | The `microsoft` mailer entry and its env bindings. |
| [`app/Console/Commands/SendTestMail.php`](../app/Console/Commands/SendTestMail.php) | `php artisan mail:test`. |

Everything that sends mail (the contact form and the dashboard's **إرسال بريد**
page) goes through Laravel's `Mail` facade, so switching `MAIL_MAILER` switches
all of it at once. Resend stays configured as a fallback: set `MAIL_MAILER=resend`
and sending reverts, with no code change.

## Falling back to SMTP (not recommended)

If an admin re-enables SMTP AUTH on the tenant, plain SMTP also works until
Microsoft removes it at the end of 2026:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=contact@asswatdjazairia.com
MAIL_PASSWORD=the-mailbox-password
```

This needs SMTP AUTH enabled for the mailbox (`Set-CASMailbox -Identity
contact@asswatdjazairia.com -SmtpClientAuthenticationDisabled $false`) and the
account excluded from MFA. It will break when Microsoft completes the retirement.
