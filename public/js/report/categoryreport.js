AOS.init();

  let modalData = [];
  let modalMonth = '';
  let modalCategory = '';

  $(document).ready(function () {
    $.ajax({
      url: '/api/monthly-quantity-category',
      method: 'GET',
      success: function (data) {
        let totalQuantity = data.reduce((sum, row) => sum + (parseInt(row.quantity) || 0), 0);
        let categoryMap = {};
        data.forEach(row => {
            categoryMap[row.category] = (categoryMap[row.category] || 0) + (parseInt(row.quantity) || 0);
        });

        let topCategory = Object.entries(categoryMap).sort((a, b) => b[1] - a[1])[0] || ['-', 0];

        // Update UI
        $("#total-quantity").text(totalQuantity);
        $("#top-category").text(topCategory[0]);
        $("#category-count").text(Object.keys(categoryMap).length);
        const tbody = $('#reportTableBody');
        tbody.html('');

        data.forEach((item, index) => {
          const row = $(`
            <tr class="opacity-0 hover:bg-white/5 transition duration-300 cursor-pointer" 
                data-month="${item.month}" 
                data-category="${item.category}">
              <td class="px-4 py-2">${formatMonth(item.month)}</td>
              <td class="px-4 py-2">${item.category}</td>
              <td class="px-4 py-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-500/20 text-blue-300">
                  ${item.total_quantity.toLocaleString()}
                </span>
              </td>
            </tr>
          `);
          tbody.append(row);

          anime({
            targets: row[0],
            opacity: [0, 1],
            translateY: [30, 0],
            delay: index * 100,
            duration: 500,
            easing: 'easeOutExpo'
          });
        });

        $('#reportTableBody tr').on('click', function() {
          modalMonth = $(this).data('month');
          modalCategory = $(this).data('category');
          openItemDetailsModal(modalMonth, modalCategory);
        });
      }
    });

    $('#exportCSV').on('click', function () {
      $.ajax({
        url: '/api/monthly-quantity-category',
        method: 'GET',
        success: function (data) {
          const csvContent = [
            ['Month', 'Category', 'Quantity'],
            ...data.map(row => [row.month, row.category, row.total_quantity])
          ].map(e => e.join(',')).join('\n');

          const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
          const link = document.createElement('a');
          link.href = URL.createObjectURL(blob);
          link.setAttribute('download', 'monthly_quantity_category_report.csv');
          document.body.appendChild(link);
          link.click();
          document.body.removeChild(link);
        }
      });
    });

    function openItemDetailsModal(month, category) {
      $('#itemDetailsModal').removeClass('hidden');
      $('#modalSubtitle').text(`${formatMonth(month)} - ${category}`);
      $('#modalContent').html(`<div class="flex items-center justify-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-400"></div></div>`);

      anime({
        targets: '#itemDetailsModal > div',
        scale: [0.8, 1],
        opacity: [0, 1],
        duration: 300,
        easing: 'easeOutExpo'
      });

      $.ajax({
        url: '/api/item-details-by-month-category',
        method: 'GET',
        data: { month, category },
        success: function(data) {
          modalData = data;
          if (data.length === 0) {
            $('#modalContent').html(`<div class="text-center py-12"><p class="text-gray-400">No items found for this month and category.</p></div>`);
            return;
          }

          const tableHtml = `
            <div class="overflow-x-auto">
              <table class="w-full table-auto">
                <thead class="bg-white/5 border-b border-white/10">
                  <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Item Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Item Code</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Quantity</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300 uppercase tracking-wider">Unit Price</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                  ${data.map((item, index) => `
                    <tr class="hover:bg-white/5 transition-colors opacity-0" style="animation-delay: ${index * 50}ms">
                      <td class="px-4 py-3 text-white font-medium">${item.item_name || '-'}</td>
                      <td class="px-4 py-3 text-white font-medium">${item.item_code || '-'}</td>
                      <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-500/20 text-emerald-300">${item.quantity || 0}</span></td>
                      <td class="px-4 py-3 text-gray-300">${item.unit_price ? '$' + parseFloat(item.unit_price).toFixed(2) : '-'}</td>
                    </tr>
                  `).join('')}
                </tbody>
              </table>
            </div>`;

          $('#modalContent').html(tableHtml);

          anime({
            targets: '#modalContent tbody tr',
            opacity: [0, 1],
            translateY: [20, 0],
            delay: anime.stagger(50),
            duration: 400,
            easing: 'easeOutExpo'
          });
        }
      });
    }

    $('#exportModalExcel').on('click', function () {
      if (!Array.isArray(modalData) || modalData.length === 0) return;
      const ws = XLSX.utils.json_to_sheet(modalData);
      const wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Details');
      const timestamp = new Date().toISOString().slice(0, 19).replace(/[-T:]/g, '');
      const filename = `details_${modalMonth}_${modalCategory}_${timestamp}.xlsx`;
      XLSX.writeFile(wb, filename);
    });

    $('#closeModal, #itemDetailsModal').on('click', function(e) {
      if (e.target === this) {
        anime({
          targets: '#itemDetailsModal > div',
          scale: [1, 0.8],
          opacity: [1, 0],
          duration: 200,
          easing: 'easeInExpo',
          complete: function() {
            $('#itemDetailsModal').addClass('hidden');
          }
        });
      }
    });

    $('#itemDetailsModal > div').on('click', function(e) {
      e.stopPropagation();
    });

    function formatMonth(month) {
      const date = new Date(month + '-01');
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long' });
    }
  });