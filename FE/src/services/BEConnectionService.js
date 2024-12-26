class BEConnectionService {
  static API_URL = "http://localhost:8080/api/";
  static instance = null;

  static getInstance() {
    if (!BEConnectionService.instance) {
      BEConnectionService.instance = new BEConnectionService();
    }
    return BEConnectionService.instance;
  }

  async getAllArticles() {
    return await this.callBE('v1/articles', 'GET');
  }

  async login(email, password) {
    const body = JSON.stringify({username: email, password: password});
    return await this.callBE('login_check', 'POST', body);
  }

  async callBE(url = '', method = 'GET', body = null, headers = {}) {
    const res = await fetch(
      BEConnectionService.API_URL + url,
      {
        method: method,
        body: body,
        headers: {
          'Content-Type': 'application/json',
          ...headers
        }
      }
    );

    if (!res.ok) {
      throw new Error('Error calling the BE.');
    }

    return await res.json();
  }
}

export default BEConnectionService;
