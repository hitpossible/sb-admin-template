$(() => {
    // let table = $('#dataTable').DataTable();

    let table = $('#dataTable').DataTable({
        ajax: {
            url: site_url('dashboard/loadProductData'),
            dataSrc: "",
        },
        columns: [
            {
                data: 'id'
            },
            {
                data: 'name'
            },
            {
                data: null,
                render: function (row) {
                    return `${row.price} ฿`;
                }
            },
            {
                data: 'qty'
            },
            {
                data: null,
                render: function (row) {
                    return '123';
                }
            }
        ]
    });

    $('#addSave').click(() => {

        let formData = $('#frmAddProduct').serialize();

        $.ajax({
            url: site_url('dashboard/saveProduct'),
            type: 'GET',
            data: formData,
            dataType: 'json',
            success: (result) => {
                if (result.result) {
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        html: result.message,
                    })


                    table.ajax.reload();
                    $('#frmAddProduct')[0].reset();
                    $('#exampleModal').modal('hide')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: result.message,
                    })
                }
            }
        })
    })

})

