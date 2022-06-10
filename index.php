<html lang="en">
    <head>
        <title>Postavleno Test Case - Redis Api</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class="container w-full mx-auto">
            <div class="mx-4 my-3 bg-gradient-to-r from-white via-blue-500 to-blue-500 shadow-sm">
                <p class="font-bold text-gray-900 py-2 px-4">
                    Redis Cache List
                </p>
            </div>
            <div class="my-3 mx-4 border border-gray-100 shadow-sm text-center">
                <div class="container mx-auto my-3" id="alert">

                </div>
                <div class="container mx-auto my-3" id="container">

                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript">
        window.addEventListener('load', function(ev) {
            fetch();
        });

        // Paste from https://code.tutsplus.com/ru/articles/how-to-make-ajax-requests-with-raw-javascript--net-4855
        // Im not good with raw js ajax
        function xhrLoad(url, callback, method = 'GET') {
            let xhr;

            if(typeof XMLHttpRequest !== 'undefined') xhr = new XMLHttpRequest();
            else {
                const versions = [
                    "MSXML2.XmlHttp.5.0",
                    "MSXML2.XmlHttp.4.0",
                    "MSXML2.XmlHttp.3.0",
                    "MSXML2.XmlHttp.2.0",
                    "Microsoft.XmlHttp"
                ]

                for(let i = 0, len = versions.length; i < len; i++) {
                    try {
                        xhr = new ActiveXObject(versions[i]);
                        break;
                    }
                    catch(e){}
                } // end for
            }

            xhr.onreadystatechange = ensureReadiness;

            function ensureReadiness() {
                if(xhr.readyState < 4) {
                    return;
                }

                if(xhr.status !== 200) {
                    return;
                }

                // all is well
                if(xhr.readyState === 4) {
                    callback(xhr);
                }
            }

            xhr.open(method, url, true);
            xhr.send('');
        }

        function getReadyElement(key, value) {
            return `<div class="flex inline-flex my-2">
                        <p class="font-bold text-gray-900 pr-3">${key}:</p>
                        <p class="font-medium text-gray-900 pr-3 ">${value}</p>
                        <button onclick="remove('${key}')" class="px-2 mx-3 rounded text-red-500 border border-red-500 hover:bg-red-500 hover:text-white cursor-pointer">
                            Delete
                        </button>
                    </div>`;
        }

        function setAlert(type, text, timeout = 2500) {
            const alertElement = alert();
            const bg = type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const border = type === 'error' ? 'border-red-700' : 'border-blue-700';

            alertElement.innerHTML = `
                <div class='${bg} ${border} py-2 px-4 border text-white'>${text}</div>
            `;

            setTimeout(() => {
                alertElement.innerHTML = '';
            }, timeout);
        }

        function alert() {
            return document.getElementById('alert');
        }

        function container() {
            return document.getElementById('container');
        }

        function fetch() {
            xhrLoad('/api/redis.php', function(result) {
                const response = JSON.parse(result.response);
                if(response.code === 200) {
                    const body = response.body ?? [];
                    if(body.length <= 0) {
                        container().innerText = 'Nothing found';
                    }
                    else {
                        let temporary = [];

                        for(let key in body) {
                            if (body.hasOwnProperty(key)) {
                                temporary.push(getReadyElement(key, body[key]));
                            }
                        }

                        container().innerHTML = temporary.join('<br>');
                        setAlert('success', 'Fetching complete');
                    }
                }
                else {
                    setAlert('error', response.message);
                    container().innerText = response;
                }
            });
        }

        function remove(key) {
            xhrLoad(`/api/redis.php?key=${key}`, function(result) {
                const response = JSON.parse(result.response);
                setAlert(
                    response.code === 200 ? 'success' : 'error',
                    response.message
                );
                fetch();
            }, 'DELETE');
        }
    </script>
</html>