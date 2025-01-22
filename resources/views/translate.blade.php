<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRT Translator</title>
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
</head>

<body>
    <h1>SRT Translator</h1>
    <p>Chọn file SRT và dịch sang tiếng Việt.</p>

    <div>
        <label for="apiKey">Nhập Gemini API Key:</label>
        <input type="text" id="apiKey" class="input-field" placeholder="Gemini API Key" />
        <br><br>
        <input type="file" id="srtFile" accept=".srt" />
        <button id="translateBtn">Dịch</button>
        <p id="status"></p>
        <div id="previewArea"
            style="border: 1px solid #ddd; padding: 10px; margin: 10px 0; max-height: 300px; overflow-y: auto;">
            <!-- Nội dung preview sẽ hiển thị tại đây -->
        </div>
        <a id="downloadLink" style="display: none;">Tải file đã dịch</a>
        <br><br>
        {{-- <a href="guide.html">Cách lấy Gemini API Key</a> --}}
    </div>

    <script src="{{ asset('assets/script.js') }}"></script>
</body>

</html>
