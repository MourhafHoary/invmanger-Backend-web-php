🧠 AI UI Design Rules – Inventory Management Dashboard
🎯 Goal

These rules define how the AI agent must design, structure, and style every UI file (HTML/CSS/Bootstrap) related to the Inventory Management System Dashboard.
The focus: modern, clean, fast, responsive, and highly usable interface for managing warehouses, products, orders, and users.

🪄 1. General Design Philosophy

Use a clean, modern, and minimalistic design.

Prioritize clarity and usability — the UI must feel professional and intuitive.

The layout must be responsive and mobile-friendly using Bootstrap 5.

Avoid clutter — keep spacing generous and consistent.

Follow a dashboard aesthetic similar to:

AdminLTE

Volt Bootstrap Dashboard

Modern Material Dashboard

🎨 2. Color Palette

Use a consistent professional theme across all pages:

Purpose	Color	Example
Primary	#2C7BE5	(Blue – Buttons, headers, highlights)
Secondary	#6C757D	(Muted text, icons)
Success	#00D97E	(Positive actions, success alerts)
Danger	#E63757	(Delete, remove, warnings)
Warning	#F6C343	(Low stock, pending)
Info	#39AFEA	(Information badges)
Background	#F8F9FA	(Page background)
Card / Panel Background	#FFFFFF	
Text	#343A40 (primary) / #6C757D (secondary)	
🧱 3. Layout & Structure Rules
3.1 Page Structure

Every page must follow this base structure:

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Custom CSS goes here */
  </style>
</head>
<body>
  <!-- Sidebar -->
  <!-- Navbar -->
  <!-- Main Content -->
  <!-- Footer -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

3.2 Sidebar

Fixed sidebar on the left.

Use a dark theme (bg-dark text-light).

Each section should use Bootstrap Icons with labels.

Active link must be visually highlighted.

3.3 Navbar

Sticky top navbar with:

Search bar

Notification icon

User profile dropdown

3.4 Main Content

Cards for each module (products, orders, stock, etc.).

Use a grid layout (Bootstrap rows and columns).

Each card must have:

Title

Optional icon

Clear action buttons (edit, delete, view)

🧩 4. Components Design Rules
Buttons

Use Bootstrap buttons with consistent design:

<button class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Add New</button>
<button class="btn btn-success btn-sm"><i class="bi bi-pencil-square"></i> Edit</button>
<button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Delete</button>


Always include icons (bi from Bootstrap Icons).

Rounded corners with soft hover transitions.

Cards

Use soft shadows and padding:

.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.05);
  transition: transform 0.2s ease;
}
.card:hover {
  transform: translateY(-3px);
}

Tables

Use table-hover, table-striped, align-middle.

Add icons for actions (edit/delete/view).

Responsive tables with table-responsive.

Forms

Use Bootstrap form controls with consistent spacing.

Labels are bold, inputs have rounded corners.

Validation feedback visible under each input.

🧭 5. Icons and Visual Language

Use Bootstrap Icons
.

Icon size: bi bi-[name] fs-5 or fs-6.

Keep icon usage consistent across all pages (e.g., 🗑 = delete, ✏️ = edit).

📱 6. Responsiveness Rules

Use Bootstrap grid system.

Collapse sidebar on small screens.

Cards stack vertically on small devices.

Text and icons scale properly for mobile.

⚙️ 7. Code Quality Rules

Indentation: 2 spaces (HTML & CSS).

Use semantic tags: <header>, <nav>, <main>, <section>, <footer>.

No inline styles (unless minimal adjustments).

Use Bootstrap utility classes (mt-3, p-2, text-center, etc.) before writing custom CSS.

Comment each section of the UI clearly:

<!-- ===== Sidebar Start ===== -->
<!-- ===== Navbar End ===== -->

🌈 8. Animations and UX

Subtle hover transitions on buttons and cards.

Use CSS transitions for smooth effects:

transition: all 0.2s ease-in-out;


Avoid flashing or distracting animations.

🧰 9. Optional Enhancements

If the AI decides to improve UI:

Use modals (.modal) for editing or adding records.

Use badges for product stock levels.

Add progress bars for warehouse capacity visualization.

Add Bootstrap Alerts for success/error messages.

🧠 10. Consistency Rules for AI Agent

When the AI is asked to create or edit a UI file:

It must always use HTML5 + Bootstrap 5 + Bootstrap Icons.

Must apply the color palette above.

Must maintain responsive layout.

Must follow component style guide (buttons, tables, forms, cards).

Must use semantic HTML and clean indentation.

Must use consistent naming conventions for classes and IDs.

Every page must look unified within the same dashboard theme.

🧾 Example Page Structure
<body class="bg-light">
  <!-- Sidebar -->
  <nav class="bg-dark text-white p-3 vh-100 position-fixed">
    <h5 class="mb-4"><i class="bi bi-box-seam"></i> Inventory</h5>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="bi bi-grid"></i> Dashboard</a></li>
      <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="bi bi-box"></i> Products</a></li>
      <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="bi bi-people"></i> Customers</a></li>
      <li class="nav-item mb-2"><a href="#" class="nav-link text-white"><i class="bi bi-truck"></i> Drivers</a></li>
    </ul>
  </nav>

  <!-- Main content -->
  <main class="ms-5 p-4">
    <div class="container-fluid">
      <h2 class="mb-4"><i class="bi bi-box"></i> Products</h2>

      <button class="btn btn-primary mb-3"><i class="bi bi-plus-lg"></i> Add Product</button>

      <div class="card p-3">
        <table class="table table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>101</td>
              <td>Plastic Cup</td>
              <td>$12</td>
              <td>200</td>
              <td>
                <button class="btn btn-success btn-sm"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</body>

🏁 Summary

Whenever the AI agent builds or edits a UI file for this Inventory Management System:

It must follow this entire style guide.

It must maintain clean code, consistent layout, and Bootstrap-based design.

The final UI must always look professional, elegant, and intuitive.