<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BDU - QLCTDTN</title>
    <link rel="stylesheet" href="{{ asset(path: 'DMMC/styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{asset('DMMC/icon-logo.png')}}">
</head>

<body>
    <!-- Header Section -->
    <header>
        <img class="logo" src="{{ asset('DMMC/logo.jpg') }}" alt="Logo Trường Đại học Bình Dương">
        <div class="content">
            <div class="title">Quản lý chương trình đào tạo ngành</div>
            <div class="divider">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div class="buttons-container">
                <button>Khoa CNTT</button>
                <button>Khoa Kinh tế</button>
                <button>Khoa Luật</button>
                <button>Khoa Logistic</button>
                <button>Khoa Dược</button>
            </div>
        </div>
    </header>

    <div>  
        <h3><i class="fa-solid fa-bars"></i> Danh mục minh chứng </h3>
       
    </div>
    <div id="tables-container"></div> <!-- Mảng bảng sẽ được chèn vào đây -->

    <div id="pagination"></div> <!-- Container cho các nút phân trang -->

    <script>
        
        let data = @json($data);
        let currentPage = 1; // Trang hiện tại
        const itemsPerPage = 1; // Số lượng tiêu chuẩn hiển thị trên mỗi trang

        renderPage();

        function renderPage() {
            const container = document.querySelector('#tables-container');
            const pagination = document.querySelector('#pagination');
            container.innerHTML = ''; // Xóa nội dung hiện tại
            pagination.innerHTML = ''; // Xóa các nút phân trang hiện tại

            // Tính toán dữ liệu cho trang hiện tại
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentItems = data.slice(startIndex, endIndex);

            // Hiển thị các tiêu chuẩn trong trang hiện tại
            currentItems.forEach(item => {
                renderTables([item]); // Gọi hàm renderTables cho từng item
            });

            // Hiển thị nút phân trang
            renderPagination();
        }

        function renderPagination() {
            const pagination = document.querySelector('#pagination');
            const totalPages = Math.ceil(data.length / itemsPerPage);

            // Nút Previous
            const prevButton = document.createElement('button');
            prevButton.textContent = 'Trước';
            prevButton.disabled = currentPage === 1; // Vô hiệu hóa nếu đang ở trang đầu
            prevButton.addEventListener('click', () => {
                currentPage--;
                renderPage();
            });
            pagination.appendChild(prevButton);

            // Nút số trang
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.classList.toggle('active', i === currentPage); // Đánh dấu trang hiện tại
                pageButton.addEventListener('click', () => {
                    currentPage = i;
                    renderPage();
                });
                pagination.appendChild(pageButton);
            }

            // Nút Next
            const nextButton = document.createElement('button');
            nextButton.textContent = 'Sau';
            nextButton.style.width = '80px';
            nextButton.disabled = currentPage === totalPages; // Vô hiệu hóa nếu đang ở trang cuối
            nextButton.addEventListener('click', () => {
                currentPage++;
                renderPage();
            });
            pagination.appendChild(nextButton);
        }

        function renderTables(data) {
            const container = document.querySelector('#tables-container');
            data.forEach(tieuChuan => {
                const table = document.createElement('table');

                // Dòng tiêu đề của bảng (các cột)
                const titleRow = document.createElement('tr');
                titleRow.innerHTML = `
                    <th>Tiêu chí</th>
                    <th>Số TT</th>
                    <th>Mã minh chứng</th>
                    <th>Minh chứng con</th>
                    <th>Tên minh chứng</th>
                    <th>Số, ngày ban hành, hoặc thời điểm khảo sát, điều tra, phỏng vấn, quan sát,…</th>
                    <th>Nơi ban hành hoặc nhóm, cá nhân thực hiện</th>
                    <th>Link</th>
                `;
                table.appendChild(titleRow);
                
                // Dòng hiển thị tiêu chuẩn (title)
                const headerRow = document.createElement('tr');
                const headerCell = document.createElement('td');
                headerCell.setAttribute('colspan', '8');
                headerCell.textContent = tieuChuan.ten_tieu_chuan;  // Hiển thị tiêu đề từ JSON
                headerCell.style.fontSize = '18px';
                headerCell.style.fontWeight = 'bold';
                headerRow.appendChild(headerCell);
                table.appendChild(headerRow);

                // Render tiêu chí
                tieuChuan.tieuChis.forEach(tieuChi => {
                    const tieuChiRow = document.createElement('tr');
                    const totalRowspan = tieuChi.minhChungs.reduce((total, item) => {
                        return total + (item.minhChungCons ? item.minhChungCons.length : 1);
                    }, 1);

                    const tieuChiCell = document.createElement('td');
                    tieuChiCell.setAttribute('rowspan', totalRowspan);
                    tieuChiCell.textContent = tieuChi.ma_tieu_chi;
                    tieuChiCell.classList.add('criteria');
                    tieuChiRow.appendChild(tieuChiCell);

                    const moTaCell = document.createElement('td');
                    moTaCell.setAttribute('colspan', '7');
                    moTaCell.textContent = tieuChi.mo_ta;
                    moTaCell.style.textAlign = 'left';
                    moTaCell.style.fontSize = '16px';
                    tieuChiRow.appendChild(moTaCell);
                    table.appendChild(tieuChiRow);

                    tieuChi.minhChungs.forEach(minhChung => {
                        if (minhChung.minhChungCons) {
                            minhChung.minhChungCons.forEach((minhChungCon, index) => {
                                const minhChungConRow = document.createElement('tr');
                                if (index === 0) {
                                    const sttCell = document.createElement('td');
                                    const minhChungConCodeCell = document.createElement('td');
                                    sttCell.rowSpan = minhChung.minhChungCons.length;
                                    minhChungConCodeCell.rowSpan = minhChung.minhChungCons.length;
                                    sttCell.textContent = minhChung.so_thu_tu;
                                    minhChungConCodeCell.textContent = minhChung.ma_minh_chung;
                                    minhChungConRow.appendChild(sttCell);
                                    minhChungConRow.appendChild(minhChungConCodeCell);
                                }
                                minhChungConRow.innerHTML += `
                                    <td>${minhChungCon.so_minh_chung}</td>
                                    <td style="font-size: 12px; text-align: start;">${minhChungCon.ten_minh_chung}</td>
                                    <td style="width: 150px;">${minhChungCon.ngay_ban_hanh}</td>
                                    <td style="font-size: 12px;">${minhChungCon.noi_ban_hanh}</td>
                                    <td style="width: 100px;">
                                        ${minhChungCon.link ? `<a href="${minhChungCon.link}" target="_blank" class="btn">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>` : ''}
                                    </td>
                                `;
                                table.appendChild(minhChungConRow);
                            });
                        } else {
                            const singleItemRow = document.createElement('tr');
                            singleItemRow.innerHTML = `
                                <td>${minhChung.so_thu_tu}</td>
                                <td>${minhChung.ma_minh_chung}</td>
                                <td>${minhChung.so_minh_chung}</td>
                                <td style="font-size: 12px; text-align: start;">${minhChungCon.ten_minh_chung}</td>
                                <td style="width: 150px;">${minhChungCon.ngay_ban_hanh}</td>
                                <td style="font-size: 12px;">${minhChungCon.noi_ban_hanh}</td>
                                <td style="width: 100px;">
                                        ${minhChungCon.link ? `<a href="${minhChungCon.link}" target="_blank" class="btn">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>` : ''}
                                </td>
                            `;
                            table.appendChild(singleItemRow);
                        }
                    });
                });
                container.appendChild(table);
            });
        }

    </script>
</body>

</html>
