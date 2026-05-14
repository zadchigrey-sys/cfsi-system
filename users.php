<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SESSION['user']['role'] !== 'administrator') {
    die("Access denied.");
}

$result = $conn->query("SELECT * FROM users
    WHERE deleted_at IS NULL
");

ob_start();
?>

<div class="flex justify-between items-center mb-6">

    <h1 class="text-2xl font-bold">
        Users Management
    </h1>

    <button onclick="openUserModal()"
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">

        Add User

    </button>

</div>

<table class="w-full bg-white shadow rounded overflow-hidden">

    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Email</th>
            <th class="p-3">Role</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>

    <?php while($row = $result->fetch_assoc()): ?>

        <tr class="border-b text-center hover:bg-gray-50">

            <td class="p-3">
                <?php echo $row['id']; ?>
            </td>

            <td class="p-3">
                <?php echo htmlspecialchars($row['name']); ?>
            </td>

            <td class="p-3">
                <?php echo htmlspecialchars($row['email']); ?>
            </td>

            <td class="p-3">
                <?php echo ucfirst($row['role']); ?>
            </td>

            <td class="p-3 space-x-2">

                <button
                    onclick="openEditUser(
                        '<?php echo $row['id']; ?>',
                        '<?php echo htmlspecialchars($row['name']); ?>',
                        '<?php echo htmlspecialchars($row['email']); ?>',
                        '<?php echo $row['role']; ?>'
                    )"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">

                    Edit

                </button>

                <a href="delete_user.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Move user to recycle bin?')"
                   class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">

                    Delete

                </a>

            </td>

        </tr>

    <?php endwhile; ?>

    </tbody>

</table>

<!-- USER MODAL -->
<div id="userModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg rounded-xl p-6 shadow-lg">

        <div class="flex justify-between items-center mb-4">

            <h2 id="userModalTitle"
                class="text-xl font-bold">

                Add User

            </h2>

            <button onclick="closeUserModal()"
                    class="text-gray-500 text-xl">

                <i class="ri-close-line"></i>

            </button>

        </div>

        <form id="userForm"
              action="save_user.php"
              method="POST"
              class="space-y-4">

            <input type="hidden"
                   name="id"
                   id="user_id_hidden">

            <div>
                <label class="block mb-1 font-medium">
                    Full Name
                </label>

                <input type="text"
                       name="name"
                       id="user_name"
                       required
                       class="w-full border rounded px-4 py-2">
            </div>

            <div>
                <label class="block mb-1 font-medium">
                    Email
                </label>

                <input type="email"
                       name="email"
                       id="user_email"
                       required
                       class="w-full border rounded px-4 py-2">
            </div>

            <div>
                <label class="block mb-1 font-medium">
                    Password
                </label>

                <input type="password"
                       name="password"
                       id="user_password"
                       class="w-full border rounded px-4 py-2">

                <small class="text-gray-500">
                    Leave blank if not changing password.
                </small>
            </div>

            <div>
                <label class="block mb-1 font-medium">
                    Role
                </label>

                <select name="role"
                        id="user_role"
                        required
                        class="w-full border rounded px-4 py-2">

                    <option value="administrator">
                        Administrator
                    </option>

                    <option value="registrar">
                        Registrar
                    </option>

                </select>
            </div>

            <button type="submit"
                    id="userSubmitBtn"
                    class="bg-[#1a3a6b] hover:bg-[#2a5298] text-white px-6 py-2 rounded">

                Save User

            </button>

        </form>

    </div>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>