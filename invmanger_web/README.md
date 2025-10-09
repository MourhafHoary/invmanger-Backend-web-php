# Inventory Manager Web Application

This application follows the MVC (Model-View-Controller) architecture pattern for better organization and maintainability.

## Project Structure

```
invmanger_web/
├── controllers/     # Contains all controller logic
│   ├── Auth.js      # Authentication controller
│   ├── login.js     # Login controller
│   ├── signup.js    # Signup controller
│   ├── verify.js    # Verification controller
│   └── forgot-password.js  # Password recovery controller
├── models/          # Contains data models
│   └── api.js       # API client for backend communication
├── views/           # Contains all HTML views
│   ├── login.html   # Login page
│   ├── signup.html  # Signup page
│   ├── verify.html  # Verification page
│   ├── forgot-password.html  # Password recovery page
│   └── home.html    # Home page after authentication
├── public/          # Public assets
│   ├── css/         # Stylesheets
│   │   └── style.css  # Main stylesheet
│   └── js/          # Client-side JavaScript
│       └── *.js     # Copies of JS files for browser access
└── index.php        # Application entry point
```

## How to Use

1. Access the application through `index.php`
2. The application will redirect to the login page
3. Users can authenticate, sign up, or recover passwords

## Development

- Models: Handle data and API communication
- Views: Present the UI to users
- Controllers: Process user input and coordinate between models and views