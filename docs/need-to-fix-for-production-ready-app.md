# Production Improvements

Here is a list of features and tech debt I would need to fix before making this app production-ready:

### 1. Organisation Owner Role
- **Current Issue**: Right now, users just belong to an organisation, but there is no concept of a manager or owner.
- **Fix**: Add an "Owner" role for organisations so that a specific user can be in charge of their company's users and manage their own team's tickets.

### 2. Role & Permission System
- **Current Issue**: UI visibility and backend logic currently use hard-coded checks for `userType` (e.g., checking if the user is an "agent").
- **Fix**: Implement a proper Role-Based Access Control (RBAC) package like `spatie/laravel-permission`. This will allow us to easily add new roles (like "Admin" or "Junior Agent") in the future without cluttering the code with manual checks.

### 3. Delete Confirmation UX
- **Current Issue**: When deleting a model (like a ticket), the app uses the default browser `window.confirm()` popup, which feels clunky and unprofessional.
- **Fix**: Replace the native browser popup with a custom, styled modal component to improve the user experience and keep users inside the app's design system.

### 4. Better Comment Features
- **Current Issue**: The comment system is currently just a basic flat timeline.
- **Fix**: Add the ability to reply directly to specific comments (nested threads) and add emoji reactions to make communication easier and more interactive.
