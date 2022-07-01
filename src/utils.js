import axios from "axios";

export default class Api{
  constructor(source){
    this._source = source,
    this._data = []
  }
  getData(){
    return axios
      .get(this._source, {
        auth: {},
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
    const valid = !input.some((item) => item.value == "");
    return valid;
  }
}