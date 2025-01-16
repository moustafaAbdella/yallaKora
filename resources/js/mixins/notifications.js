export const notifications = {
  methods: {
    showError: function (value) {
      if (typeof value === "object" && value.status == 422) {
        let first = Object.keys(value.data.errors)[0];
        value = value.data.errors[first][0];
      }
      if (typeof value === 'undefined'){
        value = "An error has occurred";
      }


      this.$toast.show(value, {
        type: "error",
        top: false,
        bottom: true,
        left: false,
        right: true,
        showClose: false,
        closeDelay: 4000
        // all of other options may go here
      });
      // this.$notify({
      //   message: value,
      //   type: "error",
      //   top: false,
      //   bottom: true,
      //   left: false,
      //   right: true,
      //   showClose: false,
      //   closeDelay: 4000
      // });
    },
    showSuccess: function (value = "success") {
      this.$toast.show(value, {
        type: "success",
        top: false,
        bottom: true,
        left: false,
        right: true,
        showClose: false,
        closeDelay: 4000
        // all of other options may go here
      });
    },
    showAlert(value) {
      this.$toast.show(value, {
        type: "warning",
        top: false,
        bottom: true,
        left: false,
        right: true,
        showClose: false,
        closeDelay: 6000
        // all of other options may go here
      });
      
    },
    showConfirm: function (value) {
      this.$swal({
        title: 'هل انت متاكد',
        text: "للتاكيد حذف العنصر اضغط علي Yes",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {
          value();
        }
      })
      
    }
  }
}