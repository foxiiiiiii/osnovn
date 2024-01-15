window.state = new Proxy(
  {
    loading: true,
    user: {}
  },
  {
    set(obj, prop, value) {
      obj[prop] = value;
    },
  }
);