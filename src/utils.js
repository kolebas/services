import axios from "axios";

//Устаревший класс
export default class Api{
  constructor(source){
    this._source = source,
    this._data = []
  }
  getData(){
    return axios
      .get(this._source, {        
        withCredentials: true,
      })
      .then(response => this._data = response.data)
  }  
}

export class Form {
  constructor(inputs) {
    this._inputs = inputs
  }
  validation(){
    const input = this._inputs.filter((item) => (item.is_required === "Y"));
    const valid = !input.some((item) => item.value == "" || item.value == null);
    return valid;
  }
}