function executeExample(e) {
  switch (e) {

      case "handleDismiss":
          const i = Swal.mixin({
              customClass: {
                  confirmButton: "btn btn-success",
                  cancelButton: "btn btn-danger me-2"
              },
              buttonsStyling: !1
          });
          return void i.fire({
              title: "Are you sure?",
              text: "You won't be able to revert this!",
              icon: "warning",
              showCancelButton: !0,
              confirmButtonText: "Yes, delete it!",
              cancelButtonText: "No, cancel!",
              reverseButtons: !0
          }).then(e => {
              e.isConfirmed ? i.fire("Deleted!", "Your file has been deleted.", "success") : e.dismiss === Swal.DismissReason.cancel && i.fire("Cancelled", "Your imaginary file is safe :)", "error")
          });
  }
}