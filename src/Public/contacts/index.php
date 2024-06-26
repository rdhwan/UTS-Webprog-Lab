<?php
require_once __DIR__ . "/../../bootstrap.php";
require_once __DIR__ . "/../../Middleware/checkAuth.php";

$_SESSION["error"] = null;

// parse query string
$sort = $_GET["sort"] ?? "asc";
$search = $_GET["search"] ?? "";


$message = $_SESSION["message"];
$_SESSION["message"] = null;

$error = $_SESSION["error"] ?? null;
$_SESSION["error"] = null;

// sort contacts, make isFavorite first
$customers = Customer::where("user_id", "=", $user->id)
    ->where("name", "like", "%$search%")
    ->orderBy("isFavorite", "desc")
    ->orderBy("name", $sort)
    ->get();
?>


<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/output.css">
    <link rel="shortcut icon" href="../images/favicon.png" type="image/x-icon">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <title>UnityBook</title>
</head>

<body class="flex flex-col h-screen min-w-full bg-[url('../images/bg-transparent.png')] bg-cover font-inter">
    <div class="flex flex-col h-96 p-6 md:p-8 md:px-64 gap-6 justify-evenly">
        <?php if ($message): ?>
            <div role="alert" class="alert alert-info">
                <span>
                    <?= $message ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div role="alert" class="alert alert-error">
                <span>
                    <?= $error ?>
                </span>
            </div>
        <?php endif; ?>

        <div class="flex flex-row items-center justify-between">
            <h1 class="text-2xl font-bold">Contact</h1>

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-circle bg-[#DFF5FF]">
                    <img src="../images/icons/profile.svg" alt="my profile" />
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 gap-2">
                    <li class="p-2">
                        <?= $user->username ?>
                    </li>
                    <hr />
                    <li>
                        <button class="flex flex-row justify-between text-red-500" onclick="logout_confirm.showModal()">
                            Log out
                            <i class="ph-bold ph-sign-out"></i>
                            </btn>

                    </li>
                </ul>
            </div>
        </div>

        <form method="get" action="/src/Public/contacts/index.php">
            <label class="input w-full bg-[#D9D9D9] flex items-center gap-2 rounded-full">
                <i class="ph ph-magnifying-glass opacity-35 text-2xl"></i>
                <input type="text" name="search" class="grow" value="<?= $search ?>" placeholder="Search contact" />
            </label>

        </form>

        <div class="flex flex-col w-full gap-2">
            <h2 class="font-bold text-[#5356FF]">Favourites</h2>
            <div class="flex flex-row gap-4 overflow-scroll disable-scroll">

                <!-- dummy -->
                <?php foreach ($customers as $customer): ?>
                    <?php if ($customer->isFavorite): ?>
                        <a href="/src/Public/contacts/edit.php?id=<?= $customer->id ?>" class="avatar placeholder">
                            <div class="bg-[#DFF5FF] text-neutral rounded-full w-16">
                                <span>
                                    <?= $customer->name[0] ?>
                                </span>
                            </div>
                        </a>
                    <?php endif ?>
                <?php endforeach ?>

                <!-- add button -->
                <button
                    class="btn btn-ghost btn-circle w-16 h-16 bg-transparent border-dashed border-2 border-black hover:border-dashed hover:border-2 hover:border-black">
                    <i class="ph ph-plus
                        text-3xl"></i>
                </button>

            </div>
        </div>

        <div class="flex flex-row items-center justify-between">
            <!-- filter button -->

            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost px-0 pr-2 text-[#5356FF]">
                    <i class="ph ph-faders-horizontal text-xl"></i>
                    Sort
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li>
                        <a href="/src/Public/contacts/index.php?sort=asc&search=<?= $search ?>"
                            class="flex flex-row justify-between font-bold">
                            A-Z <i class="ph-bold ph-sort-ascending"></i></a>
                    </li>
                    <li>
                        <a href="/src/Public/contacts/index.php?sort=desc&search=<?= $search ?>"
                            class="flex flex-row justify-between font-bold">
                            Z-A <i class="ph-bold ph-sort-descending"></i></a>
                    </li>
                </ul>
            </div>

            <p class="font-bold">
                <?= count($customers) ?> entries
            </p>
        </div>


    </div>

    <div
        class="relative flex flex-1 flex-col gap-3 p-6 md:p-8 md:px-64 pb-20 bg-gradient-to-b from-[#67C6E3] to-[#5356FF] rounded-t-3xl overflow-scroll overflow-y-scroll disable-scroll shadow-inner">
        <!-- dummy contact list -->
        <?php foreach ($customers as $customer): ?>
            <!-- contact lists -->
            <div class="flex flex-row items-center justify-between bg-white rounded-full p-4 shadow-md">
                <div class="avatar placeholder">
                    <div class="bg-[#DFF5FF] text-neutral rounded-full w-12">
                        <span>
                            <?= $customer->name[0] ?>
                        </span>
                    </div>
                </div>

                <div class="flex flex-col flex-1 px-4">
                    <p>
                        <?= $customer->name ?>
                    </p>
                    <p class=" text-gray-400 font-medium">
                        <?= $customer->phoneNumber ?>
                    </p>
                </div>

                <div class="flex flex-row items-center">

                    <?php if ($customer->isFavorite): ?>
                        <i class="ph-fill ph-star text-yellow-500"></i>
                    <?php endif ?>
                    <div class="dropdown dropdown-left">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-sm">
                            <i class="ph-fill ph-dots-three-outline-vertical"></i>
                        </div>
                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52 gap-2">
                            <li>
                                <a href="/src/Public/contacts/edit.php?id=<?= $customer->id ?>"
                                    class="flex flex-row justify-between text-blue-500">
                                    Edit User
                                    <i class="ph ph-pencil-simple-line"></i>
                                </a>
                            </li>
                            <li>
                                <button onclick="<?= "delete_modal_" . $customer->id ?>.showModal()"
                                    class="flex flex-row justify-between text-red-500">
                                    Delete User
                                    <i class="ph ph-trash"></i>
                                </button>
                            </li>

                            <dialog id="<?= "delete_modal_" . $customer->id ?>" class="modal">
                                <div class="modal-box">
                                    <h3 class="font-bold text-lg">Delete
                                        <?= $customer->name ?> ?
                                    </h3>
                                    <p class="py-4">Are you sure want to delete
                                        <?= $customer->name ?>
                                    </p>
                                    <div class="modal-action">
                                        <form method="dialog">
                                            <!-- if there is a button in form, it will close the modal -->
                                            <a href="/src/Public/contacts/process_delete.php?id=<?= $customer->id ?>"
                                                class="btn btn-error">Delete</a>
                                            <button class="btn">Close</button>
                                        </form>
                                    </div>
                                </div>
                            </dialog>

                            <hr>

                            <li>

                                <?php if ($customer->isFavorite): ?>
                                    <a href="/src/Public/contacts/process_toggle_fav.php?id=<?= $customer->id ?>"
                                        class="flex flex-row justify-between text-red-400">
                                        Remove from Favourites
                                        <i class="ph ph-star"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="/src/Public/contacts/process_toggle_fav.php?id=<?= $customer->id ?>"
                                        class="flex flex-row justify-between text-yellow-500">
                                        Add to Favourites
                                        <i class="ph ph-star"></i>
                                    </a>
                                <?php endif ?>

                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        <?php endforeach ?>


        <a href="./add.php" class="fixed bottom-0 right-0 btn btn-circle w-16 h-16 m-8">
            <i class="ph ph-user-circle-plus text-4xl"></i>
        </a>

    </div>

    <dialog id="logout_confirm" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Logout confirmation</h3>
            <p class="py-4">Are you sure you want to logout?</p>
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <a href="/src/Public/auth/process_logout.php" class="btn btn-error">Logout</a>
                    <button class="btn">Close</button>
                </form>
            </div>
        </div>
    </dialog>

</body>

</html>