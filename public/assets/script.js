document.getElementById("translateBtn").addEventListener("click", async () => {
    const apiKey = document.getElementById('apiKey').value;
    const fileInput = document.getElementById("srtFile");
    const status = document.getElementById("status");
    const downloadLink = document.getElementById("downloadLink");
    const previewArea = document.getElementById("previewArea");

    // Reset giao diện
    status.textContent = "";
    previewArea.innerHTML = "";
    previewArea.style.display = "none";
    downloadLink.style.display = "none";
    status.classList.remove("text-error", "text-green", "text-warning");
    // Kiểm tra API Key và file
    if (!apiKey) {
        alert('Vui lòng nhập Gemini API Key');
        return;
    }

    if (!fileInput.files.length) {
        status.textContent = "Vui lòng chọn file SRT.";
        status.classList.add("text-error"); // Thêm lớp để đổi màu
        return;
    }

    const file = fileInput.files[0];

    // Kiểm tra định dạng file
    if (!file.name.endsWith('.srt')) {
        status.textContent = "File phải có định dạng .srt.";
        status.classList.add("text-error"); // Thêm lớp để đổi màu
        return;
    }

    const reader = new FileReader();

    reader.onload = async function () {
        const srtContent = reader.result;
        status.textContent = "Đang xử lý file...";
        status.classList.remove("text-error", "text-green", "text-warning");
        status.classList.add("text-warning");

        try {
            const response = await fetch("/api/translate", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    inputContent: srtContent,
                    apiKey: apiKey,
                }),
            });

            if (!response.ok) {
                throw new Error("Dịch thất bại.");
            }

            const data = await response.json();
            const translatedContent = data.translatedContent;

            // Hiển thị preview khi dịch thành công
            previewArea.style.display = "block";
            previewArea.textContent = translatedContent;

            // Đổi tên file tải xuống
            const originalFileName = file.name.replace('.srt', '');
            const newFileName = `${originalFileName}.vn.srt`;

            // Tạo file tải về
            const blob = new Blob([translatedContent], { type: "text/plain" });
            const url = URL.createObjectURL(blob);

            downloadLink.href = url;
            downloadLink.download = newFileName;
            downloadLink.textContent = "Tải file đã dịch";
            downloadLink.style.display = "block";

            status.textContent = "Dịch thành công!";
            status.classList.remove("text-error", "text-green", "text-warning");
            status.classList.add("text-green"); // Thêm lớp để đổi màu
        } catch (error) {
            status.textContent = "Dịch thất bại. Vui lòng thử lại.";
            status.classList.remove("text-error", "text-green", "text-warning");
            status.classList.add("text-error"); // Thêm lớp để đổi màu
            console.error(error);
        }
    };

    reader.readAsText(file);
});

// Hàm tách nội dung SRT thành các đoạn nhỏ
function splitSRTContent(srtContent, charLimit) {
    const lines = srtContent.split('\n');
    const parts = [];
    let currentPart = '';

    for (const line of lines) {
        if ((currentPart.length + line.length + 1 > charLimit) && line.trim() === '') {
            parts.push(currentPart.trim());
            currentPart = '';
        }
        currentPart += line + '\n';
    }

    if (currentPart) {
        parts.push(currentPart.trim());
    }

    return parts;
}
