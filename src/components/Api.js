import axios from "axios";

export default class Api {
  constructor() {
  }

  getData(data) {
    const { url, headers, method, params, auth } = data;
    return axios(url, {
      headers: headers,
      method: method,
      params: params,
      auth: auth
    })
      .then(this._checkResponse)
      .then((data) => {
        return data;
      })
  }
  updateData(formData) {
    const { url, headers, method, data } = formData;
    return axios(url, {
      headers: headers,
      method: method,
      data: data,
    })
      .then(this._checkResponse)
      .then((data) => {
        return data;
      })
  }

  sendData(formData) {
    const { url, headers, method, params, data } = formData;
    return axios(url, {
      headers: headers,
      method: method,
      params: params,
      data: data,
    })
      .then(this._checkResponse)
      .then((data) => {
        return data;
      })
  }

  _checkResponse(res) {
    if (res.status === 200) {
      return res;
    }
    return Promise.reject(`Ошибка: ${res.status}`);
  }
}