# O Patusco - Veterinary Clinic Appointment Management System

A modern appointment scheduling and management system for veterinary clinics, built with Laravel, Vue 3, and Inertia.js. This application solves the problem of long waiting queues by enabling online appointment booking and intelligent scheduling.

## 🎯 Overview

O Patusco is designed to streamline the appointment process for the veterinary clinic by providing:

- **Client Portal**: Easy online appointment booking
- **Receptionist Dashboard**: Complete appointment management and veterinarian assignment
- **Veterinarian Dashboard**: View and manage assigned appointments
- **Real-time Conflict Detection**: Prevents double-booking and scheduling conflicts
- **Role-Based Access Control**: Secure access with three distinct user roles

## 🛠️ Tech Stack

### Backend

- **Framework**: Laravel 12 (can be downgraded, nothing specific of 12 was used)
- **Database**: MySQL
- **Authentication**: Laravel Breeze (session-based)
- **Authorization**: Laravel Policies and Gates
- **Testing**: PHPUnit

### Frontend

- **Framework**: Vue 3 (Composition API)
- **Full-stack Integration**: Inertia.js
- **UI Components**: Vuetify 3
- **Icons**: Material Design Icons (MDI)
- **Styling**: Tailwind CSS
- **Type Safety**: TypeScript

## 📋 Features

### 1. Client Features

Clients can create appointment requests with the following information:

- Personal details (name, email)
- Pet information (name, type, age)
- Health concerns (symptoms)
- Preferred appointment slot (date and time period)

### 2. Receptionist Features

The receptionist dashboard includes:

- View all appointment requests and scheduled consultations
- Filter consultations by date and animal type
- Assign appointment requests to veterinarians
- Create, edit, and delete consultations
- Conflict detection for veterinarian scheduling
- Time slot validation (08:00 - 17:30, 30-minute intervals)

### 3. Veterinarian Features

Veterinarians have access to:

- Personal dashboard showing assigned appointments
- Filter consultations by date and animal type
- Edit appointment notes and details
- Read-only access (cannot delete appointments)
- View appointment history and client notes

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Node.js 16+
- Composer

### Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd patusco
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node.js dependencies**

    ```bash
    npm install
    ```

4. **Set up environment file**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Create database and run migrations**

    ```bash
    php artisan migrate
    ```

6. **Seed the database** (optional)

    ```bash
    php artisan db:seed
    ```

7. **Build frontend assets**

    ```bash
    npm run dev
    ```

8. **Start the development server**
    ```bash
    php artisan serve
    ```

Visit `http://localhost:8000` in your browser.

## 📖 Usage Guide

### Authentication

The system supports three user roles:

| Role             | Description         | Permissions                                       |
| ---------------- | ------------------- | ------------------------------------------------- |
| **Cliente**      | Pet owner           | Create appointment requests                       |
| **Rececionista** | Clinic receptionist | Manage all consultations, assign to veterinarians |
| **Veterinário**  | Veterinarian        | View & edit assigned consultations                |

Login with:

- cliente@example.com password
- rececionista@example.com password
- veterinario@example.com password

Or create a consultation a a guest

- (email used) password

### Client Workflow

1. Navigate to the booking form
2. Fill in personal and pet information
3. Describe symptoms or concerns
4. Select preferred date and time period
5. Submit appointment request
6. Receive confirmation

### Receptionist Workflow

1. View incoming appointment requests in dashboard
2. Review consultation details (client info, pet details, symptoms)
3. Select an available veterinarian
4. Choose available date and time slot
5. System validates for conflicts
6. Confirm and create consultation
7. Manage existing consultations (edit/delete as needed)

### Veterinarian Workflow

1. Access personal dashboard
2. View all assigned appointments
3. Filter by date or animal type
4. Click on appointment to view details
5. Add or edit professional notes
6. Cannot delete appointments (receptionist only)

## 🧪 Testing

The project includes comprehensive automated tests ensuring code quality and reliability.

### Running Tests

```bash
# Run all tests
npm run test

# Run specific test class
php artisan test tests/Unit/Policies/ConsultationPolicyTest.php
```

### Test Structure

**Unit Tests**: Authorization policies and business logic

- `ConsultationPolicyTest`: Authorization rules for appointments
- `ConsultationRequestPolicyTest`: Authorization for booking requests

**Feature Tests**: HTTP controllers and full workflows

- `ReceptionistControllerTest`: Receptionist endpoints and CRUD operations
- `VeterinarianControllerTest`: Veterinarian dashboard and operations
- `ClientControllerTest`: Client booking workflow

### Test Coverage

The project aims for comprehensive coverage of:

- Authorization policies (who can do what)
- CRUD operations for consultations
- Validation and error handling
- Role-based access control

## 🔒 Security Features

- **Laravel Breeze**: Session-based authentication with built-in security
- **Policies**: Fine-grained authorization for all resources
- **Role-Based Access**: Custom middleware for role enforcement (client, receptionist, veterinarian)
- **Validation**: Server-side input validation on all endpoints
- **CSRF Protection**: Cross-site request forgery prevention on all forms
- **Mass Assignment Protection**: Guarded model attributes
- **Password Security**: Bcrypt hashing and reset functionality
- **Email Verification**: Optional email verification during registration

## 📱 Appointment Scheduling Rules

The system enforces the following scheduling constraints:

- **Operating Hours**: 08:00 - 17:30
- **Time Slots**: 30-minute intervals
- **Conflict Prevention**: No veterinarian double-booking
- **Validation**: Client-side and server-side validation
- **Feedback**: Real-time error messages for conflicts

## 🎨 UI/UX Features

- Responsive design with Tailwind CSS
- Material Design icons for visual clarity
- Vuetify components for consistency
- Accessible form controls
- Loading states and error handling
- Notifications for user feedback

## 📝 Code Style

- **PHP**: PSR-12 via Laravel Pint
- **JavaScript/Vue**: ESLint configuration (see `.eslintrc`)
- **CSS**: Tailwind CSS conventions
- **Database**: Laravel migration conventions

## 🐛 Known Issues & Future Enhancements

### Planned Features

- Email notifications
- Veterinarian availability calendar
- Recurring appointment support
- Advanced reporting and analytics
- Multi-clinic support
- Calendar view

### Issues

- Some Vuetify components aren't working as intended due to not importing the styles (they were conflicting with tailwind)
- Some frontend components wore rushed and are not properly component-ised (time restrictions)
- Clients cannot delete their animals (Didn't implement soft deletes in time)
- There is some lack of consistancy on the visual side (again no time for proper componentisation, and some ai help for faster development)
- No model scopes were used, never used them before, and couldn't learn in time
- No Form Resources were used, forms are behing handled in vue with useForm

## 👥 Authors

- Project created for a show of skill of Daniel Silva
- Built with Laravel, Vue 3, and Inertia.js best practices

**Version**: 1.0.0
