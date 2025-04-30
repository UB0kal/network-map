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
    <div class="h-screen w-3/12 bg-gray-300" >
        <div class="flex p-5 items-center justify-center bg-slate-50">
            <div>
            <h1 class="text-xl mb-3">Create new device</h1>
            <form class="space-y-4">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="network">Network</label>
                <select id="network" name="network" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    <option value="new">new</option>
                </select>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="device">Device</label>
                <select name="device" id="device" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                    <option value="new">Switch</option>
                    <option value="new">Router</option>
                    <option value="new">Hub</option>
                    <option value="new">Repeater</option>
                    <option value="new">Modem</option>
                    <option value="new">Access point</option>
                </select>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="name">Name</label>
                <input class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" type="text" id="name" name="name" placeholder="Name">
                <label for="ipAddress" class="block text-sm font-medium text-gray-700 mb-1">IP Address</label>
                <input type="text" id="ipAddress" name="ipAddress" placeholder="192.168.1.1" maxlength="15" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm">
                <label class="block text-sm font-medium text-gray-700 mb-1" for="description">Description</label>
                <input class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none sm:text-sm" type="text" id="description" name="description" placeholder="Description">
                <input class="border-2 rounded-md p-2 hover:scale-110 bg-slate-800 border-slate-800 text-gray-300" type="submit" value="create device">
            </form>
            </div>
        </div>
    </div>
    <div class="w-9/12 overflow-hidden">
    <script src="//cdn.jsdelivr.net/npm/force-graph"></script>
    <div id="graph"></div>
    <script>
    // Random tree
    const N = 300;
    const gData = {
      nodes: [...Array(N).keys()].map(i => ({ id: i })),
      links: [...Array(N).keys()]
        .filter(id => id)
        .map(id => ({
          source: id,
          target: Math.round(Math.random() * (id-1))
        }))
    };

    const Graph = new ForceGraph()
      (document.getElementById('graph'))
        .linkDirectionalParticles(2)
        .graphData(gData);
  </script>
    </div>
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