<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tra cứu vi phạm giao thông</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/vi.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-blue-100 via-white to-blue-50 min-h-screen py-12">
    <div class="max-w-5xl mx-auto px-4">

        <article class="prose prose-lg mx-auto mb-8 text-gray-800">
            <p class="lead">
                <strong>Phạt nguội</strong> đã trở thành một phần quan trọng trong hệ thống giám sát giao thông tại Việt
                Nam. Với hệ thống camera giám sát hiện đại, các hành vi vi phạm được ghi lại và xử phạt, ngay cả khi
                người vi phạm không bị bắt tại chỗ.
            </p>
        </article>

        <div class="text-center mb-12">
            <div class="flex justify-center mb-4">
                <i class="fas fa-shield-alt text-5xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4 tracking-tight">
                Tra cứu vi phạm giao thông
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Hệ thống tra cứu thông tin vi phạm giao thông trực tuyến
            </p>
        </div>

        <!-- Enhanced Search Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 transform hover:shadow-2xl transition-all duration-300">
            <form id="searchForm" class="space-y-6">
                <div>
                    <label for="plateNumber" class="block text-lg font-medium text-gray-700 mb-3">
                        <i class="fas fa-car-side mr-2 text-blue-600"></i>Biển số xe
                    </label>
                    <input type="text" id="plateNumber" name="bienso" required
                        class="w-full px-5 py-4 text-lg border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                        placeholder="Nhập biển số xe (VD: 51F-123.45 hoặc 51F12345)">
                </div>
                <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium py-4 px-6 rounded-xl transition-all duration-300 text-lg flex items-center justify-center group">
                    <i class="fas fa-search mr-2 group-hover:scale-110 transition-transform"></i>
                    <span>Kiểm tra ngay</span>
                </button>
            </form>
        </div>

        <!-- Loading Spinner -->
        <div id="loading" class="hidden">
            <div class="flex justify-center items-center py-16">
                <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent"></div>
            </div>
        </div>

        <!-- Results -->
        <div id="results" class="space-y-6 hidden"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {
            const formatDate = (dateStr) => {
                return moment(dateStr, 'YYYY-MM-DD HH:mm:ss').format('HH:mm [ngày] DD [tháng] MM [năm] YYYY');
            };

            const getStatusClass = (status) => {
                return status.includes('Đã') ?
                    'text-green-700 bg-green-50 border border-green-200' :
                    'text-yellow-700 bg-yellow-50 border border-yellow-200';
            };

            const getStatusIcon = (status) => {
                return status.includes('Đã') ?
                    '<i class="fas fa-check-circle mr-1"></i>' :
                    '<i class="fas fa-clock mr-1"></i>';
            };

            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                let bienso = $('#plateNumber').val().trim();

                if (!bienso) {
                    showError('Vui lòng nhập biển số xe');
                    return;
                }

                $('#loading').removeClass('hidden');
                $('#results').addClass('hidden');

                $.ajax({
                    url: '/api/tra-cuu',
                    method: 'POST',
                    data: {
                        bienso: bienso,
                    },
                    dataType: 'json',
                    success: function(response) {
                        try {
                            if (response.error) {
                                showError(response.error);
                                return;
                            }

                            if (!Array.isArray(response) || !response.length) {
                                $('#results').html(`
                                <div class="bg-green-50 border border-green-200 p-6 rounded-xl flex items-center">
                                    <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                                    <p class="text-green-700 text-lg">Không tìm thấy thông tin vi phạm cho biển số ${bienso}</p>
                                </div>
                            `).removeClass('hidden');
                                return;
                            }

                            const violationsHtml = response.map((violation, index) => `
                            <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-xl transition-all duration-300">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="font-semibold text-xl text-gray-800 flex items-center">
                                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                                        Vi phạm ${index + 1}/${response.length}
                                    </h3>
                                    <span class="px-4 py-2 rounded-full ${getStatusClass(violation['Trạng thái'])} flex items-center">
                                        ${getStatusIcon(violation['Trạng thái'])}${violation['Trạng thái']}
                                    </span>
                                </div>
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <p class="font-medium text-gray-700 mb-2">
                                                <i class="fas fa-license-plate mr-2 text-blue-600"></i>Biển số:
                                            </p>
                                            <p class="text-gray-800">${violation['Biển kiểm soát']}</p>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <p class="font-medium text-gray-700 mb-2">
                                                <i class="fas fa-palette mr-2 text-blue-600"></i>Màu biển:
                                            </p>
                                            <p class="text-gray-800">${violation['Màu biển']}</p>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <p class="font-medium text-gray-700 mb-2">
                                                <i class="fas fa-car mr-2 text-blue-600"></i>Loại xe:
                                            </p>
                                            <p class="text-gray-800">${violation['Loại phương tiện']}</p>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <p class="font-medium text-gray-700 mb-2">
                                                <i class="far fa-clock mr-2 text-blue-600"></i>Thời gian:
                                            </p>
                                            <p class="text-gray-800">${formatDate(violation['Thời gian vi phạm'])}</p>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="font-medium text-gray-700 mb-2">
                                            <i class="fas fa-map-marker-alt mr-2 text-blue-600"></i>Địa điểm:
                                        </p>
                                        <p class="text-gray-800">${violation['Địa điểm vi phạm']}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="font-medium text-gray-700 mb-2">
                                            <i class="fas fa-exclamation-circle mr-2 text-blue-600"></i>Hành vi vi phạm:
                                        </p>
                                        <p class="text-gray-800">${violation['Hành vi vi phạm']}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="font-medium text-gray-700 mb-2">
                                            <i class="fas fa-building mr-2 text-blue-600"></i>Đơn vị phát hiện:
                                        </p>
                                        <p class="text-gray-800">${violation['Đơn vị phát hiện vi phạm']}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <p class="font-medium text-gray-700 mb-2">
                                            <i class="fas fa-map-marked-alt mr-2 text-blue-600"></i>Nơi giải quyết:
                                        </p>
                                        <ul class="space-y-2 pl-6">
                                            ${Array.isArray(violation['Nơi giải quyết vụ việc']) ?
                                                violation['Nơi giải quyết vụ việc'].map(location =>
                                                    `<li class="text-gray-800 flex items-start">
                                                                                        <i class="fas fa-circle text-xs mt-1.5 mr-2 text-blue-600"></i>
                                                                                        ${location}
                                                                                    </li>`
                                                ).join('') :
                                                `<li class="text-gray-800">${violation['Nơi giải quyết vụ việc']}</li>`
                                            }
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `).join('');

                            $('#results').html(violationsHtml).removeClass('hidden');
                        } catch (e) {
                            showError('Đã xảy ra lỗi khi xử lý dữ liệu');
                            console.error('Error:', e);
                        }
                    },
                    error: function(xhr, status, error) {
                        showError('Không thể kết nối đến máy chủ');
                        console.error('Error:', error);
                    },
                    complete: function() {
                        $('#loading').addClass('hidden');
                    }
                });
            });

            function showError(message) {
                $('#results').html(`
                <div class="bg-red-50 border border-red-200 p-6 rounded-xl flex items-center">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                    <p class="text-red-700 text-lg">${message}</p>
                </div>
            `).removeClass('hidden');
            }
        });
    </script>
</body>

</html>
