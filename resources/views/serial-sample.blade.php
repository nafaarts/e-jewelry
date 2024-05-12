<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Serial</title>
</head>

<body>=
    <button id="open-serial">Request Serial Port</button>

    <button id="close-serial">Stop</button>

    <hr />

    <h4 id="output-serial"></h4>

    <script>
        if ("serial" in navigator) {
            // The Web Serial API is supported.
            console.log("Web Serial API is supported");

            document.querySelector('#open-serial').addEventListener('click', async () => {
                // Prompt user to select any serial port.
                // const port = await navigator.serial.requestPort();

                // Filter on devices with the Arduino Uno USB Vendor/Product IDs.
                const filters = [{
                        usbVendorId: 0x2341,
                        usbProductId: 0x0043
                    },
                    {
                        usbVendorId: 0x2341,
                        usbProductId: 0x0001
                    }
                ];

                // Prompt user to select an Arduino Uno device.
                const port = await navigator.serial.requestPort({
                    filters
                });

                const {
                    usbProductId,
                    usbVendorId
                } = port.getInfo();

                const bufferSize = 1024; // 1kB
                let buffer = new ArrayBuffer(bufferSize);

                // Set `bufferSize` on open() to at least the size of the buffer.
                await port.open({
                    baudRate: 9600,
                    bufferSize
                });

                const reader = port.readable.getReader({
                    mode: "byob"
                });
                while (true) {
                    const {
                        value,
                        done
                    } = await reader.read(new Uint8Array(buffer));
                    if (done) {
                        break;
                    }
                    buffer = value.buffer;
                    // Handle `value`.

                    // Output the value to the console.
                    const decoder = new TextDecoder();
                    console.log(decoder.decode(value));
                    console.log(buffer, value);
                }

                // Close the port.
                await port.close();
            });


        } else {
            // The Web Serial API is not supported.
            console.log("Web Serial API is not supported");
        }
    </script>
</body>

</html>
