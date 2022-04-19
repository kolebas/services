import axios from "axios";
class UserInfo{
  constructor(source){
    this._source = source;
  }
  getData(){
    return axios
      .get(this._source, {
        auth: {},
      })
      .then(response => response.data)
  }
}

export { UserInfo }