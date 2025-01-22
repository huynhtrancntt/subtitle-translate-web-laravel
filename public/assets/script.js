document.getElementById("translateBtn").addEventListener("click", async () => {
    const apiKey = document.getElementById('apiKey').value;
    const fileInput = document.getElementById("srtFile");
    const status = document.getElementById("status");
    const downloadLink = document.getElementById("downloadLink");
    const previewArea = document.getElementById("previewArea");
    const progressBar = document.getElementById("progressBar");
    const progressContainer = document.getElementById("progressContainer");

    // Reset giao diện
    status.textContent = "";
    previewArea.innerHTML = "";
    previewArea.style.display = "none";
    downloadLink.style.display = "none";
    progressBar.style.width = "0%";
    progressBar.setAttribute("aria-valuenow", "0");
    progressBar.textContent = "0%";
    progressContainer.style.display = "none";
    status.classList.remove("text-error", "text-green", "text-blue");

    // Kiểm tra API Key và file
    if (!apiKey) {
        alert('Vui lòng nhập Gemini API Key');
        return;
    }

    if (!fileInput.files.length) {
        status.textContent = "Vui lòng chọn file SRT.";
        status.classList.add("text-error");
        return;
    }

    const file = fileInput.files[0];

    // Kiểm tra định dạng file
    if (!file.name.endsWith('.srt')) {
        status.textContent = "File phải có định dạng .srt.";
        status.classList.add("text-error");
        return;
    }

    const reader = new FileReader();

    reader.onload = async function () {
        const srtContent = reader.result;
        status.textContent = "Đang xử lý file...";
        status.classList.add("text-blue");

        // Hiển thị thanh tiến trình
        progressContainer.style.display = "block";

        // Tách nội dung file SRT thành các đoạn nhỏ
        const CHARACTER_PER_BATCH = 1000; // Giới hạn ký tự mỗi đoạn
        const parts = splitSRTContent(srtContent, CHARACTER_PER_BATCH);
        const totalParts = parts.length;

        const translatedParts = [];
        previewArea.innerHTML = ""; // Reset preview trước khi dịch

        for (let i = 0; i < totalParts; i++) {
            status.textContent = `Đang dịch đoạn ${i + 1}/${totalParts}...`;

            const startTime = Date.now(); // Bắt đầu đo thời gian

            try {
                const response = await fetch("/api/translate", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        inputContent: parts[i],
                        apiKey: apiKey,
                    }),
                });

                const apiTime = Date.now() - startTime; // Đo thời gian gọi API
                console.log(`Thời gian gọi API đoạn ${i + 1}: ${apiTime}ms`);

                if (!response.ok) throw new Error(`Lỗi khi dịch đoạn ${i + 1}`);

                const data = await response.json();
                const translatedPart = data.translatedContent;

                // Lưu đoạn dịch và hiển thị trong preview
                translatedParts.push(translatedPart);
                const previewElement = document.createElement("p");
                previewElement.innerHTML = translatedPart.replace(/\n/g, "<br>");
                previewArea.appendChild(previewElement);

                // Cập nhật thanh tiến trình
                const progress = Math.round(((i + 1) / totalParts) * 100);
                progressBar.style.width = `${progress}%`;
                progressBar.setAttribute("aria-valuenow", progress);
                progressBar.textContent = `${progress}%`;

                // Cuộn tự động
                previewArea.scrollTop = previewArea.scrollHeight;
                previewArea.style.display = "block";
            } catch (error) {
                console.error(error);
                translatedParts.push(`// Lỗi khi dịch đoạn ${i + 1}`);
                const errorElement = document.createElement("p");
                errorElement.textContent = `Lỗi khi dịch đoạn ${i + 1}`;
                errorElement.style.color = "red";
                previewArea.appendChild(errorElement);
            }
        }

        // Tạo file đã dịch sau khi hoàn thành
        const translatedContent = translatedParts.join("\n");
        const blob = new Blob([translatedContent], { type: "text/plain" });
        const url = URL.createObjectURL(blob);

        // Hiển thị link tải file
        const newFileName = file.name.replace('.srt', '.vi.srt');
        downloadLink.href = url;
        downloadLink.download = newFileName;
        downloadLink.textContent = "Tải file đã dịch";
        downloadLink.style.display = "block";

        // Cập nhật thanh tiến trình hoàn tất
        progressBar.style.width = "100%";
        progressBar.setAttribute("aria-valuenow", "100");
        progressBar.textContent = "Hoàn thành!";
        progressContainer.style.display = "none";

        status.textContent = "Dịch thành công!";
        status.classList.remove("text-error", "text-blue");
        status.classList.add("text-green");
    };

    reader.readAsText(file);
});

// Hàm tách nội dung SRT thành các phần nhỏ
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
