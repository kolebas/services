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