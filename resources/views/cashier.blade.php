<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Kasir</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-md rounded-lg p-6 max-w-2xl w-full ml-4 mr-4">
            <h2 class="text-2xl font-bold mb-4">Kasir</h2>
            <div class="mb-4">
                <label for="nominal" class="block text-sm font-medium text-gray-700">Masukkan Jumlah Belanja</label>
                <input type="text" id="nominal" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" autocomplete="off" value="0">
            </div>
            <button type="button" id="btn_submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Submit</button>
            <div id="wrap_payment" class="mt-7">
            </div>
        </div>

        <script type="module">
            $(function() {
                $('#nominal').on('keyup', function() {
                    let value = $(this).val()

                    value = value.replace(/^0+/, '')
                    value = value.replace(/[^0-9]/g, '')
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                    value = value == '' ? 0 : value
                    $(this).val(value)
                })

                $('#btn_submit').click(function() {
                    $('#btn_submit').LoadingOverlay('show')
                    $.ajax({
                        url: '/calculate',
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {nominal: $('#nominal').val()},
                        success: function(response) {
                            let data = response.data
                            let divPayment = ''

                            data.forEach(function(value) {
                                divPayment += `
                                    <div class="bg-gray-100 shadow-md p-2 w-36 text-center">
                                        ${value}
                                    </div>
                                `
                            })

                            $('#wrap_payment').html(`
                                <h4 class="text-md font-bold">Kemungkinan Pembayaran</h4>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4">
                                    ${divPayment}
                                </div>
                            `)
                        },
                        error: function(xhr, status, error) {
                            alert('Error')
                        },
                        complete: function(xhr, status) {
                            $('#btn_submit').LoadingOverlay('hide')
                        }
                    })
                })
            })
        </script>
    </body>
</html>