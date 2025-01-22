<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRT Translator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>

<body class="bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-md">
                    <div class="card-body">
                        <h1 class="text-center text-primary mb-4">SRT Translator</h1>
                        <p class="text-center">Chọn file SRT và dịch sang tiếng Việt.</p>
                        <form>
                            <div class="mb-3">
                                <label for="apiKey" class="form-label">Nhập Gemini API Key:</label>
                                <input type="text" id="apiKey" class="form-control"
                                    value="AIzaSyBw1UEyprOT0XHy4cT2KFFNguDgLuoN4fk" placeholder="Gemini API Key" />
                            </div>
                            <div class="mb-3">
                                <label for="srtFile" class="form-label">Chọn file SRT:</label>
                                <input type="file" id="srtFile" class="form-control" accept=".srt" />
                            </div>
                            <div class="d-grid">
                                <button type="button" id="translateBtn" class="btn btn-primary">Dịch</button>
                            </div>
                        </form>
                        <div class="progress mt-3" style="height: 20px; display: none;" id="progressContainer">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                                role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100">
                                0%
                            </div>
                        </div>
                        <p id="status" class="mt-3 text-center text-muted"></p>
                        <div id="previewArea" class="border rounded mt-3 p-3"
                            style="max-height: 300px; overflow-y: auto; display: none;">
                            <!-- Nội dung preview sẽ hiển thị tại đây -->
                        </div>
                        <div class="mt-3 text-center">
                            <a id="downloadLink" class="btn btn-success" style="display: none;">Tải file đã dịch</a>
                        </div>
                        {{-- <div class="mt-3 text-center">
                            <a href="guide.html" class="text-secondary">Cách lấy Gemini API Key</a>
                        </div> --}}
                    </div>
                </div>


            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
</body>

</html>
