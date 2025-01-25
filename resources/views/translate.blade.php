<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRT Translator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
    <style>
        .upload-container {
            border: 2px dashed #ccc;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
        }

        .upload-container:hover {
            border-color: rgb(0, 200, 255);
        }

        .upload-container input[type="file"] {
            display: none;
        }

        .upload-container label {
            background-color: rgb(0, 200, 255);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-container label:hover {
            background-color: rgb(0, 200, 255);
        }

        .upload-container p {
            font-size: 16px;
            margin-top: 10px;
        }

        #progressContainer {
            display: none;
        }
    </style>

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
                            <div class="mb-3 d-flex align-items-center">
                                <label for="apiKey" class="form-label me-3">Nhập Gemini API Key:</label>
                                <input type="text" id="apiKey" class="form-control me-3" value=""
                                    placeholder="Gemini API Key" style="max-width: 300px;" />

                            </div>

                            <div class="mb-3">
                                <div class="justify-content-center align-items-center ">
                                    <div class="upload-container">
                                        <h3>Kéo & Thả Tệp Vào Đây</h3>
                                        <p>hoặc nhấp vào nút Chọn Tệp ở dưới</p>
                                        <input type="file" id="srtFile" accept=".srt" />
                                        <label for="srtFile" for="srtFile">Chọn file SRT</label>
                                    </div>
                                </div>
                                {{-- <label for="srtFile" class="form-label">Chọn file SRT:</label>
                                <input type="file" id="srtFile" class="form-control" accept=".srt" />  --}}
                            </div>
                            <div class="d-grid" style="display: none;">
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
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
</body>

</html>
