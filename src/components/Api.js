import axios from "axios";

export default class Api {
  constructor (){
  }

  getData(data) {
    const {url, headers, method, params} = data;
    return axios(url, {      
      headers: headers,
      method: method,
      params: params
    })
      .then(this._checkResponse)
      .then((data) => {
        return data;
      }) 
  }

  sendData(data) {
    const {url, headers, method, params} = data;
    return axios(url, {      
      headers: headers,
      method: method,
      params: params
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