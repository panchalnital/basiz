<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Task 1 - Student Details</title>
<link rel="stylesheet" href="styles.css"/>
</head>
<body>
<div class="wrap">
  <a class="back" href="/basiz/index.php">&larr; Back to assignments</a>
  <div class="card">
    <h1>Add Student</h1>
    <form id="studentForm" novalidate>
      <div id="formMsg"></div>

      <div class="field">
        <label>Name *</label>
        <input type="text" name="name" id="name">
        <div class="error" id="err_name"></div>
      </div>

      <div class="field">
        <label>Gender *</label>
        <select name="gender" id="gender">
          <option value="">-- Select --</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
        <div class="error" id="err_gender"></div>
      </div>

      <div class="field">
        <label>Standard *</label>
        <input type="text" name="standard" id="standard" placeholder="e.g. 10th">
        <div class="error" id="err_standard"></div>
      </div>

      <div class="field">
        <label>Date of Birth *</label>
        <input type="date" name="dob" id="dob">
        <div class="error" id="err_dob"></div>
      </div>

      <div class="field">
        <label>Age (auto calculated)</label>
        <input type="text" id="age" readonly>
        <div class="error"></div>
      </div>

      <div class="field">
        <label>Father Name *</label>
        <input type="text" name="father_name" id="father_name">
        <div class="error" id="err_father_name"></div>
      </div>

      <div class="field">
        <label>Father Mobile Number *</label>
        <input type="text" name="father_mobile" id="father_mobile" maxlength="10" placeholder="10 digit number">
        <div class="error" id="err_father_mobile"></div>
      </div>

      <div class="field">
        <label>Email *</label>
        <input type="email" name="email" id="email">
        <div class="error" id="err_email"></div>
      </div>

      <button type="submit">Add Student</button>
    </form>
  </div>

  <div class="card">
    <h1>Students List</h1>
    <table id="studentsTable">
      <thead>
        <tr>
          <th>#</th><th>Name</th><th>Gender</th><th>Standard</th><th>DOB</th>
          <th>Age</th><th>Father Name</th><th>Father Mobile</th><th>Email</th>
        </tr>
      </thead>
      <tbody id="studentsBody">
        <tr><td colspan="9">Loading...</td></tr>
      </tbody>
    </table>
  </div>
</div>

<script>
const dobInput = document.getElementById('dob');
const ageInput = document.getElementById('age');

// Auto-calculate age from DOB and current date
dobInput.addEventListener('change', function () {
  const dob = new Date(this.value);
  if (isNaN(dob.getTime())) { ageInput.value = ''; return; }
  const today = new Date();
  let age = today.getFullYear() - dob.getFullYear();
  const m = today.getMonth() - dob.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
  ageInput.value = age >= 0 ? age : '';
});

function clearErrors() {
  document.querySelectorAll('.error').forEach(el => el.textContent = '');
  const msg = document.getElementById('formMsg');
  msg.style.display = 'none';
  msg.className = '';
  msg.textContent = '';
}

function clientValidate() {
  let ok = true;
  const name = document.getElementById('name').value.trim();
  const gender = document.getElementById('gender').value;
  const standard = document.getElementById('standard').value.trim();
  const dob = document.getElementById('dob').value;
  const fatherName = document.getElementById('father_name').value.trim();
  const mobile = document.getElementById('father_mobile').value.trim();
  const email = document.getElementById('email').value.trim();

  if (!name) { document.getElementById('err_name').textContent = 'Name is mandatory.'; ok = false; }
  if (!gender) { document.getElementById('err_gender').textContent = 'Gender is mandatory.'; ok = false; }
  if (!standard) { document.getElementById('err_standard').textContent = 'Standard is mandatory.'; ok = false; }
  if (!dob) { document.getElementById('err_dob').textContent = 'Date of birth is mandatory.'; ok = false; }
  if (!fatherName) { document.getElementById('err_father_name').textContent = 'Father name is mandatory.'; ok = false; }
  if (!/^[0-9]{10}$/.test(mobile)) { document.getElementById('err_father_mobile').textContent = 'Must be exactly 10 numeric digits.'; ok = false; }
  const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!email || !emailRe.test(email)) { document.getElementById('err_email').textContent = 'Valid email is mandatory.'; ok = false; }

  return ok;
}

document.getElementById('studentForm').addEventListener('submit', function (e) {
  e.preventDefault();
  clearErrors();
  if (!clientValidate()) return;

  const formData = new FormData(this);

  // AJAX call from JS to PHP (XMLHttpRequest)
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'add_student.php', true);
  xhr.onload = function () {
    const msg = document.getElementById('formMsg');
    try {
      const res = JSON.parse(xhr.responseText);
      if (res.success) {
        msg.className = 'success';
        msg.textContent = res.message;
        msg.style.display = 'block';
        document.getElementById('studentForm').reset();
        ageInput.value = '';
        loadStudents();
      } else {
        msg.className = 'error';
        msg.textContent = res.message;
        msg.style.display = 'block';
        if (res.errors) {
          for (const field in res.errors) {
            const el = document.getElementById('err_' + field);
            if (el) el.textContent = res.errors[field];
          }
        }
      }
    } catch (err) {
      msg.className = 'error';
      msg.textContent = 'Unexpected server response.';
      msg.style.display = 'block';
    }
  };
  xhr.send(formData);
});

function loadStudents() {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'get_students.php', true);
  xhr.onload = function () {
    const tbody = document.getElementById('studentsBody');
    try {
      const res = JSON.parse(xhr.responseText);
      if (res.success && res.data.length) {
        tbody.innerHTML = res.data.map((s, i) => `
          <tr>
            <td>${i + 1}</td>
            <td>${escapeHtml(s.name)}</td>
            <td>${escapeHtml(s.gender)}</td>
            <td>${escapeHtml(s.standard)}</td>
            <td>${escapeHtml(s.dob)}</td>
            <td>${escapeHtml(String(s.age))}</td>
            <td>${escapeHtml(s.father_name)}</td>
            <td>${escapeHtml(s.father_mobile)}</td>
            <td>${escapeHtml(s.email)}</td>
          </tr>`).join('');
      } else {
        tbody.innerHTML = '<tr><td colspan="9">No students found.</td></tr>';
      }
    } catch (err) {
      tbody.innerHTML = '<tr><td colspan="9">Failed to load students.</td></tr>';
    }
  };
  xhr.send();
}

function escapeHtml(str) {
  const div = document.createElement('div');
  div.textContent = str;
  return div.innerHTML;
}

// initial load
loadStudents();
</script>
</body>
</html>
