const apiUrl = "http://localhost/Todo/todo.php";

window.onload = function () {
  loadTodos();
};

function loadTodos() {
  fetch(apiUrl)
    .then((res) => res.json())
    .then((data) => {
      let list = document.getElementById("todoList");
      list.innerHTML = "";

      data.forEach((todo) => {
        let li = document.createElement("li");
        let todoClass = todo.status === "done" ? "done" : "";

        li.innerHTML = `
            <span class="${todoClass}">${todo.title}</span>
            <br>
            <small>Status: ${todo.status || "pending"}</small>
            <br><br>
            <button onclick="markDone(${todo.id})">Done</button>
            <button onclick="deleteTodo(${todo.id})">Delete</button>
        `;
        list.appendChild(li);
      });
    });
}

function addTodo() {
  let title = document.getElementById("todoTitle").value;

  if (title.trim() === "") {
    alert("Todo cannot be empty!");
    return;
  }

  fetch(apiUrl, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ title: title }),
  })
    .then((res) => res.json())
    .then((data) => {
      alert(data.message);
      document.getElementById("todoTitle").value = "";
      loadTodos();
    });
}

function markDone(id) {
  fetch(apiUrl + "?id=" + id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ status: "done" }),
  })
    .then((res) => res.json())
    .then((data) => {
      alert(data.message);
      loadTodos();
    });
}

function deleteTodo(id) {
  fetch(apiUrl, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ id: id }),
  })
    .then((res) => res.json())
    .then((data) => {
      alert(data.message);
      loadTodos();
    });
}
