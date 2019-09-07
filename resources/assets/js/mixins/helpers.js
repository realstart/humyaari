export default {
  methods: {
    checkToken() {
      let token = localStorage.getItem('token');
	    return token ? token : null;
    },
    setDate(fieldName, object, key) {
      const newValue = jQuery("[name=" + fieldName + "]").val();
      if (object === null) {
        this[key] = newValue;
        return;
      }
      object[key] = newValue;
    },
  }
}
