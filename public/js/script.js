$(document).ready(function () {
  $("#kelompok").change(function () {
    $.ajax({
      type: "POST",
      url: "pendaftar/fakultas",
      data: {
        kelompok: $("#kelompok").val(),
      },
      success: function (response) {
        $("#fakultas").empty();
        $("#fakultas").append(response);
      },
    });
  });
});

function getMahasiswa() {
  let angkatan = $('[name="angkatan"]').val();
  $.ajax({
    type: "GET",
    url: "/ubahEmail/getMahasiswa",
    data: { angkatan: angkatan },
    dataType: "json",
    beforeSend: function (e) {
      if (e && e.overrideMimeType) {
        e.overrideMimeType("application/json;charset=UTF-8");
      }
    },
    success: function (response) {
      let data = response.data;
      let status = response.status;
      let message = response.message;
      $('[name="npm"]').empty();
      $('[name="npm"]').append('<option value="">-- Select --</option>');
      if (status) {
        data.forEach((mhs) => {
          $('[name="npm"]').append(
            '<option value="' +
              mhs.Register_Number +
              "-" +
              mhs.Nim +
              '">' +
              mhs.Full_Name +
              " (" +
              mhs.Nim +
              ")</option>",
          );
        });
      } else {
        alert(message);
      }
      $('[name="npm"]').selectpicker("refresh");
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
}

function getTagihan() {
  let mhs = $('[name="npm"]').val();
  let num = mhs.split("-")[0];
  $.ajax({
    type: "POST",
    url: "https://api.umsu.ac.id/Simakad/tagihan",
    data: {
      RegisterNumber: num,
    },
    dataType: "json",
    beforeSend: function (e) {
      if (e && e.overrideMimeType) {
        e.overrideMimeType("application/json;charset=UTF-8");
      }
    },
    success: function (response) {
      let data = response.data;
      let status = response.status;
      let message = response.message;
      let html = "";
      let no = 1;
      $("#dataTagihan").empty();
      if (status) {
        data.forEach((mhs) => {
          html += "<tr>";
          html += '<td class="text-center">' + no + "</td>";
          html += "<td>" + mhs.Term_Year_Bill_id + "</td>";
          html += "<td>" + mhs.Cost_Item_Name + "</td>";
          html +=
            "<td>" +
            (mhs.Payment_Order == null ? "-" : mhs.Payment_Order) +
            "</td>";
          html += "<td>" + formatRupiah(mhs.Amount) + "</td>";
          html += "</tr>";
          no++;
        });
      } else {
        html += "<tr>";
        html += '<td class="text-center" colspan="5">Data tidak ditemukan</td>';
        html += "</tr>";
        if (num != "") {
          alert(message);
        }
      }
      $("#dataTagihan").html(html);
    },
    error: function (e) {
      alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
    },
  });
}

function formatRupiah(number) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(number);
}
