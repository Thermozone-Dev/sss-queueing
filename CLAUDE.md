# SSS Queueing and Appointment System Specification

## Core APIs

- Transaction API
- Member Information API
- Branch API

---

## Roles / Users

- super_admin
- member
- head_office
- branch_head
- branch_staff

---

## Core Pages

- Appointment Page
- Registration Page / Modal

---

## General Rules

- A member can only have one (1) appointment per day
- One appointment can include multiple transactions
- If a station accommodates both priority lanes (Senior Citizens / PWD) and appointments:
  - Priority lanes must always be served first (legal compliance)
- If a transaction involves linked stations:
  - Apply global queue logic (First-In, First-Out)
  - Member proceeds to the next station based on original queue timestamp

---

## Head Office Admin (Main Control)

### Branch Configuration

- Pull branch data from API
- Store raw responses
- Map required fields
- Assign transactions available per branch
- Enable or disable appointment portal per branch
- Configure:
  - Working hours
  - Office days

### Other Controls

- Activate or deactivate branches
- System uses single database tenancy

---

## Branch Admin

### Station Management

- Configure stations
- Assign allowed transactions
  - Must be selected only from Head Office configuration
- Assign station staff

### Queue Configuration

- Define allowed queue types:
  - Appointment
  - Priority
  - Walk-in
- Set maximum queue capacity per station

---

## Appointment Page

### UI Behavior

- Disable dates based on:
  - Operating days
  - Maximum appointment threshold
- Disable entire calendar if fully booked

### Time Selection

- Only within business hours
- Fully booked time slots are disabled

### Required Fields

- Transaction
- Branch
- Member ID

### Notifications

- Send email to registered SSS email

---

## Appointment Module

### Features

- Manage all appointments
- Branch Admin can:
  - Override and allow no-show users to queue

---

## Appointment Maintenance Settings

### Time Intervals

- 30 minutes
- 1 hour
- 3 hours

### Capacity

- Number of appointments per interval

### Grace Period

- Defined in minutes
- Automatically tag as `no_show` if exceeded

### Rebooking Rules

- Same Day
- Next Business Day

### Cancellation Rules

- Allowed within:
  - 1 day before
  - 12 hours before
  - 6 hours before

---

## Registration Flow

1. Input SSS Number
2. Fetch email or phone via API
3. Send OTP for verification
4. Set password

---

## Data Syncing

### Transaction Pulling

- Fetch transaction data from API
- Map and store locally

### Station Pulling

- Fetch station-related data
- Map and store locally

---

## API Response Model

Standardized response types:

- member
- transaction
- branch

---

## Appointment Cancellation

- Allowed only within configured cancellation window
- Based on system-defined rules

---

## Maintenance

### Appointment Status (Default)

- active
- no_show
- cancelled

### Cancellation Types

- Configurable categories for tracking

---

## Technology Stack

- Backend: Laravel (TALL Stack)
- Admin Panel: Filament
- Frontend: Vue.js
- Architecture: Single Database Tenancy
- MySQL Database Structure

---

## Key System Principles

- Centralized queue management (FIFO with priority override)
- Configurable per-branch behavior
- API-driven data with local mapping
- Scalable multi-station support
- Strict enforcement of appointment policies
