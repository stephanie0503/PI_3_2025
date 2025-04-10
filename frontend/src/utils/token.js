// obter o token JWT do localStorage
export function getToken() {
  return localStorage.getItem("jwt_token");
}

// definir o token JWT no localStorage
export function setToken(token) {
  localStorage.setItem("jwt_token", token);
}

// remover o token JWT do localStorage
export function removeToken() {
  localStorage.removeItem("jwt_token");
}
