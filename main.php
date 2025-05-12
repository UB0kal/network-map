<?php
    session_start();

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header("Location: login.php");
        exit();
    }

    $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User'; // Get username, default to 'User'
    $user_id = $_SESSION['user_id'];

    require "getDbData.php";

    getDevices();
    getConnections();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="main.css">
</head>
<body class="min-h-screen flex">
    <div class="h-screen flex flex-col w-3/12 bg-gray-300" >
        <div class="flex p-5 items-center justify-center bg-slate-50">
            <div>
            <h1 class="text-xl mb-3 cursor-pointer">Select network:</h1>
            <form action="./processDbData.php" method="POST">
                <div class="flex mb-4 space-x-2">
                <select id="network" name="networkName" 
                class="mt-1block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    <option value="null">select network</option>
                    <?php 
                        $listed_networks = getNetworks();
                        foreach($listed_networks as $network){
                            echo("<option value=". $network['id']);
                            if (isset($_SESSION['selected_network']) and $_SESSION['selected_network'] == $network['id']){
                                echo(" selected='selected'");
                            } 
                            echo(">" . $network['name'] . "</option>");
                        }
                    ?>
                </select>
                <input class="border-2 rounded-md px-2 hover:scale-110 bg-slate-800 border-slate-800 text-gray-300" type="submit" value="Go">
                </div>
            </form>
            <h1 class="text-xl mb-3 cursor-pointer" id="CreateNetwork">Create new network</h1>
            <form action="./addnetwork.php" method="POST" class="space-y-4 pb-5 hidden" id="CreateNetworkDiv">
                <input class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" type="text" id="NetworkName" name="NetworkName" placeholder="Network name">
                <input class="border-2 rounded-md p-2 hover:scale-110 bg-slate-800 border-slate-800 text-gray-300" type="submit" value="create new network">
            </form>
            <div
                <?php 
                    if(!isset($_SESSION['selected_network'])){
                        echo('class="hidden"');
                    }
                ?>
            >
                <h1 class="text-xl mb-3 cursor-pointer" id="CreateDevice">Create new device</h1>
                <form action="./addDevice.php" method="POST" class="space-y-4 hidden" id="CreateDeviceDiv">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="device">Device</label>
                    <select name="type" id="device" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                        <option value="Switch">Switch</option>
                        <option value="Routerew">Router</option>
                        <option value="Hub">Hub</option>
                        <option value="Repeater">Repeater</option>
                        <option value="Modem">Modem</option>
                        <option value="Access Point">Access point</option>
                    </select>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Name</label>
                    <input class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" type="text" id="name" name="name" placeholder="Name">
                    <label for="ipAddress" class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                    <input type="text" id="ipAddress" name="ipAddress" placeholder="192.168.1.1" maxlength="15" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="description">Description</label>
                    <input class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" type="text" id="description" name="description" placeholder="Description">
                    <input class="border-2 p-2 hover:scale-110 bg-slate-800 border-slate-800 text-gray-300" type="submit" value="create device">
                </form>

                <h1 class="text-xl mb-3 cursor-pointer" id="CreateConnection">Connect</h1>
                <form action="./addConnection.php" method="POST" class="space-y-4 hidden" id="CreateConnectionDiv">
                    <select id="relationfrom" name="from" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                        <?php 
                            $devices = json_decode($_COOKIE['devices'], true);
                            foreach($devices as $device){
                                echo("<option value='". $device['id'] . "'>" . $device['name'] . "</option>");
                            }
                        ?>
                    </select>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="relationto">Is connected to:</label>
                    <select id="relationto" name="to" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                        <?php 
                            $devices = json_decode($_COOKIE['devices'], true);
                            foreach($devices as $device){
                                echo("<option value='". $device['id'] . "'>" . $device['name'] . "</option>");
                            }
                        ?>
                    </select>
                    <input class="border-2 p-2 hover:scale-110 bg-slate-800 border-slate-800 text-gray-300" type="submit" value="Connect">
                </form>
            </div>
            </div>
        </div>
        <div class="flex flex-col mt-auto w-full items-center justify-center ">
            <h1 id="nodeName"></h1>
            <h1 id="nodeIP"></h1>
            <h1 id="nodeType"></h1>
            <h1 id="nodeDescription"></h1>
            <form class="hidden" id="nodeDelete" action='./removeDbLines.php' method='post'>
                    <input class='border-2 scale-75 p-3 mb-3 hover:scale-100 rounded-lg bg-red-600 border-red-600 text-gray-200' type='submit' name='removeNode' value='delete Node'>
                </form>
        </div>
        <div class="flex flex-col mt-auto w-full items-center justify-center ">
            <div>
                <form action="./removeDbLines.php" method="post">
                    <input class="border-2 p-3 mb-3 hover:scale-110 rounded-lg bg-red-600 border-red-600 text-gray-200" type="submit" name="removeNetwork" value="delete network">
                </form>
            </div>
            <div class="flex flex-col w-full p-5 items-center justify-center bg-slate-50">
                <h1 class="text-xl mb-3 cursor-pointer" id="userWidget">Preferences</h1>
                <div id="userWidgetDiv" class="hidden space-y-5">
                    <div class="flex hover:scale-125 items-center space-x-2">
                        <img src="icons/user-icon.svg" alt="user" class="w-10 h-10">
                        <h1 class="text-md"><?= $_SESSION["username"]?></h1>
                    </div>
                    <form action="./logout.php"class="flex items-center justify-center hover:scale-125 space-x-3 cursor-pointer">
                        <label for="logOut" class="cursor-pointer">
                            <img id="logOut" src="icons/log-out-icon.svg" alt="log-out" class="w-5 h-5">
                        </label>
                        <input type="submit" value="Log out" class="cursor-pointer text-red-600">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="w-9/12 overflow-hidden">
    <?php include "graph.php" ?>
    </div>



<!-- logic for navigation -->
<script>
    const CreateDevice = document.getElementById('CreateDevice')
    const CreateDeviceDiv = document.getElementById('CreateDeviceDiv')
    CreateDevice.addEventListener('click', function(){
        if  (CreateDeviceDiv.classList.contains('hidden')) {
            CreateDeviceDiv.classList.remove('hidden');
        }
        else {
            CreateDeviceDiv.classList.add('hidden')
        }
    })

    const CreateConnection = document.getElementById('CreateConnection')
    const CreateConnectionDiv = document.getElementById('CreateConnectionDiv')
    CreateConnection.addEventListener('click', function(){
        if  (CreateConnectionDiv.classList.contains('hidden')) {
            CreateConnectionDiv.classList.remove('hidden');
        }
        else {
            CreateConnectionDiv.classList.add('hidden')
        }
    })

    const CreateNetwork = document.getElementById('CreateNetwork')
    const CreateNetworkDiv = document.getElementById('CreateNetworkDiv')
    CreateNetwork.addEventListener('click', function(){
        if  (CreateNetworkDiv.classList.contains('hidden')) {
            CreateNetworkDiv.classList.remove('hidden');
        }
        else {
            CreateNetworkDiv.classList.add('hidden')
        }
    })

    const userWidget = document.getElementById('userWidget')
    const userWidgetDiv = document.getElementById('userWidgetDiv')
    userWidget.addEventListener('click', function(){
        if  (userWidgetDiv.classList.contains('hidden')) {
            userWidgetDiv.classList.remove('hidden');
        }
        else {
            userWidgetDiv.classList.add('hidden')
        }
    })

</script> 

<!-- logic for ip handling -->
    <script>
        const ipInput = document.getElementById('ipAddress');

        const ipPattern = /^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        let previousValue = '';

        function setInputValidity(isValid) {
            if (isValid) {
                ipInput.classList.remove('invalid', 'border-red-500');
                ipInput.classList.add('border-gray-300');
            } else {
                ipInput.classList.add('invalid', 'border-red-500');
                ipInput.classList.remove('border-gray-300');
            }
        }

        ipInput.addEventListener('input', (event) => {
            const input = event.target;
            let value = input.value;
            const cursorPos = input.selectionStart;
            let cursorOffset = 0;

            let cleanedValue = value.replace(/[^0-9.]/g, '');
            cleanedValue = cleanedValue.replace(/\.+/g, '.'); 
            if (cleanedValue.startsWith('.')) cleanedValue = cleanedValue.substring(1);

            let parts = cleanedValue.split('.');
            let formattedValue = '';
            let isCurrentlyValid = true;

            parts.slice(0, 4).forEach((part, index) => {
                if (part.length > 3) {
                    part = part.substring(0, 3);
                     if (cursorPos > formattedValue.length + 3) cursorOffset -= (part.length - 3);
                }

                if (part.length > 0 && (!/^\d+$/.test(part) || parseInt(part, 10) > 255)) {
                    isCurrentlyValid = false;
                }

                formattedValue += part;

                if (part.length === 3 && index < 3 && cleanedValue.charAt(formattedValue.length) !== '.') {
                     const typingForward = value.length > previousValue.length;
                     const justCompletedOctet = typingForward && cursorPos === formattedValue.length;
                     if (justCompletedOctet || parts.length > index + 1) {
                         formattedValue += '.';
                         if (cursorPos >= formattedValue.length - 1) cursorOffset++;
                     }
                } else if (index < 3 && cleanedValue.charAt(formattedValue.length) === '.') {
                    formattedValue += '.';
                }
            });

             if (formattedValue.length > 15) {
                 formattedValue = formattedValue.substring(0, 15);
             }

            if (input.value !== formattedValue) {
                input.value = formattedValue;
                const newCursorPos = Math.max(0, Math.min(cursorPos + cursorOffset, formattedValue.length));
                try {
                    input.setSelectionRange(newCursorPos, newCursorPos);
                } catch (e) { console.warn("Cursor setting failed:", e); }
            }

            const looksComplete = parts.length === 4 && parts.every(p => p.length > 0);
            setInputValidity(isCurrentlyValid && (!looksComplete || ipPattern.test(formattedValue)));

            previousValue = input.value;
        });
        ipInput.addEventListener('focus', () => {
            previousValue = ipInput.value;
        });

    </script>

</body>
</html>