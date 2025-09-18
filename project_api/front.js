// student call function (fetching)
const btnStudent = document.getElementById("btn_student");

async function getStudent() {
    try {
        const response = await fetch("Student/getStudent.php");
        const student_data = await response.json();
        console.log(student_data);

        const table = document.getElementById("stud");
        renderTable(table, student_data, 'No student data found.', "stud_id");
    } catch (error) {
        console.error("Error fetching student data:", error);
    }
}
btnStudent.addEventListener("click", getStudent);

// program call function (fetching)
const btnProgram = document.getElementById("btn_program");

async function getProgram() {
    try {
        const response = await fetch("Program/getProgram.php");
        const program_data = await response.json();
        console.log(program_data);

        const table = document.getElementById("prog");
        renderTable(table, program_data, 'No program data found.', "program_id");
    } catch (error) {
        console.error("Error fetching program data:", error);
    }
}
btnProgram.addEventListener("click", getProgram);

// enrollment call function (fetching)
const btnEnrollment = document.getElementById("btn_enrollment");

async function getEnrollment() {
    try {
        const response = await fetch("Enrollment/getEnrollment.php");
        const enrollment_data = await response.json();
        console.log(enrollment_data);

        const table = document.getElementById("enroll");
        renderTable(table, enrollment_data, 'No enrollment data found.', "enrollment_id");
    } catch (error) {
        console.error("Error fetching enrollment data:", error);
    }
}
btnEnrollment.addEventListener("click", getEnrollment);

// year call function(fetching)
const btnyear = document.getElementById("btn_year");

async function getYear() {
    try {
        const response = await fetch("Year_semester/getYear.php");
        const year_data = await response.json();
        console.log(year_data);

        const table = document.getElementById("Year");
        renderTable(table, year_data, 'No year data found.', "year_id");
    } catch (error) {
        console.error("Error fetching year data:", error);
    }
}
btnyear.addEventListener("click", getYear);

// semester call function(fetching)
const btnsemester = document.getElementById("btn_semester");

async function getSemester() {
    try {
        const response = await fetch("semester_year/getSemester.php");
        const semester_data = await response.json();
        console.log(semester_data);

        const table = document.getElementById("semster");
        renderTable(table, semester_data, 'No semester data found.', "sem_id");
    } catch (error) {
        console.error("Error fetching semester data:", error);
    }
}
btnsemester.addEventListener("click", getSemester);

// subject call function(fetching)
const btnSubject = document.getElementById("btn_subject");

async function getSubject() {
    try {
        const response = await fetch("Subject/getSubject.php");
        const subject_data = await response.json();
        console.log(subject_data);

        const table = document.getElementById("subject");
        renderTable(table, subject_data, 'No subject data found.', "subject_id");
    } catch (error) {
        console.error("Error fetching subject data:", error);
    }
}
btnSubject.addEventListener("click", getSubject);

//////////////////////////////////////////////////////////////////////////////////////////

// Generic function to render table
function renderTable(table, dataArray, emptyMsg, primaryKey) {
    table.innerHTML = '';

    if (!Array.isArray(dataArray) || dataArray.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');
        cell.colSpan = 1;
        cell.textContent = emptyMsg;
        row.appendChild(cell);
        table.appendChild(row);
        return;
    }

    const keys = Object.keys(dataArray[0]);

    // Create thead
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');

    const selectTh = document.createElement('th');
    selectTh.textContent = "Select";
    headerRow.appendChild(selectTh);

    keys.forEach(key => {
        const th = document.createElement('th');
        th.textContent = key;
        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Create tbody
    const tbody = document.createElement('tbody');
    dataArray.forEach(obj => {
        const row = document.createElement('tr');

        // Radio button column, unique per table
        const selectTd = document.createElement('td');
        selectTd.innerHTML = `<input type="radio" name="selected_${primaryKey}" value="${obj[primaryKey]}">`;
        row.appendChild(selectTd);

        keys.forEach(key => {
            const td = document.createElement('td');
            td.textContent = obj[key] ?? '';
            row.appendChild(td);
        });

        tbody.appendChild(row);
    });
    table.appendChild(tbody);
}
//////////////////////////////////////////////////////////////////////////////////////////
// =========================
// STUDENT
// =========================

const studentForm = document.getElementById("studentForm");
const responseDiv = document.getElementById("response");

studentForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(studentForm);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("Student/insertStudent.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });

        const text = await response.text();
        let result;
        try { result = JSON.parse(text); }
        catch { result = { success: false, message: "Invalid JSON: " + text }; }

        responseDiv.style.display = "block";
        responseDiv.textContent = result.message || "No message received";
        responseDiv.style.color = result.success ? "green" : "red";

        if (result.success) {
            studentForm.reset();
            getStudent();
            emptyMsgsu();
        }
    } catch (error) {
        console.error("Error inserting student:", error);
    }
});

function emptyMsgsu() {
    setTimeout(() => {
        responseDiv.style.display = "none";
        responseDiv.textContent = "";
    }, 5000);
}

async function editStudentHandler() {
    let selected = document.querySelector("input[name='selected_stud_id']:checked");
    if (!selected) return alert("Please select a student first!");

    let newFirstName = prompt("Enter new first name:");
    let newMiddleName = prompt("Enter new middle name:");
    let newLastName = prompt("Enter new last name:");
    let newAllowance = prompt("Enter new allowance:");

    if (newFirstName && newMiddleName && newLastName && newAllowance) {
        let response = await fetch("Student/edithStudent.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({
                stud_id: selected.value,
                first_name: newFirstName,
                middle_name: newMiddleName,
                last_name: newLastName,
                allowance: newAllowance
            })
        });

        let result = await response.json();
        alert(result.message);
        getStudent();
    }
}
document.getElementById("editBtnStudent").addEventListener("click", editStudentHandler);

async function deleteStudentHandler() {
    let selected = document.querySelector("input[name='selected_stud_id']:checked");
    if (!selected) return alert("Please select a student first!");

    let stud_id = selected.value;
    if (!confirm("Are you sure you want to delete this student?")) return;

    try {
        const response = await fetch("Student/deleteStudent.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ stud_id }),
        });

        const result = await response.json();
        alert(result.message);
        getStudent();
    } catch (error) {
        console.error("Error deleting student:", error);
    }
}
document.getElementById("deleteBtnStudent").addEventListener("click", deleteStudentHandler);

//////////////////////////////////////////////////////////////////////////////////////////
// =========================
// SEMESTER CRUD
// =========================

const semesterForm = document.getElementById("semesterForm");
const semesterResponseDiv = document.getElementById("semesterResponse");

semesterForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(semesterForm);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("semester_year/insertsemester.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });

        const text = await response.text();
        let result;
        try { result = JSON.parse(text); }
        catch { result = { success: false, message: "Invalid JSON: " + text }; }

        semesterResponseDiv.style.display = "block";
        semesterResponseDiv.textContent = result.message || "No message received";
        semesterResponseDiv.style.color = result.success ? "green" : "red";

        if (result.success) {
            semesterForm.reset();
            getSemester();
            emptyMsgSemester();
        }
    } catch (error) {
        console.error("Error inserting semester:", error);
    }
});

function emptyMsgSemester() {
    setTimeout(() => {
        semesterResponseDiv.style.display = "none";
        semesterResponseDiv.textContent = "";
    }, 5000);
}

async function editSemesterHandler() {
    let selected = document.querySelector("input[name='selected_sem_id']:checked");
    if (!selected) return alert("Please select a semester first!");

    let newName = prompt("Enter new semester name:");
    let newYearId = prompt("Enter new year_id:");

    const response = await fetch("semester_year/editsemester.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            sem_id: selected.value,
            sem_name: newName,
            year_id: newYearId
        })
    });

    const result = await response.json();
    alert(result.message);
    getSemester();
}
document.getElementById("editBtnSemester").addEventListener("click", editSemesterHandler);

async function deleteSemesterHandler() {
    let selected = document.querySelector("input[name='selected_sem_id']:checked");
    if (!selected) return alert("Please select a semester first!");

    let sem_id = selected.value;
    if (!confirm("Are you sure you want to delete this semester?")) return;

    const response = await fetch("semester_year/deletesemester.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ sem_id }),
    });

    const result = await response.json();
    alert(result.message);
    getSemester();
}
document.getElementById("deleteBtnSemester").addEventListener("click", deleteSemesterHandler);

//////////////////////////////////////////////////////////////////////////////////////////
// =========================
// ENROLLMENT CRUD
// =========================

const enrollment = document.getElementById("enrollmentForm");
const enrollmentResponseDiv = document.getElementById("response1");

enrollment.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(enrollment);
  const data = Object.fromEntries(formData.entries());

  try {
    const response = await fetch("Enrollment/enrollStudent.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const text = await response.text();
    let result;
    try { result = JSON.parse(text); }
    catch { result = { success: false, message: "Invalid JSON: " + text }; }

    enrollmentResponseDiv.style.display = "block";
    enrollmentResponseDiv.textContent = result.message || "No message received";
    enrollmentResponseDiv.style.color = result.success ? "green" : "red";

    if (result.success) {
      enrollment.reset();
      getEnrollment();
      emptyMsgEnrollment();
    }
  } catch (error) {
    console.error("Error inserting enrollment:", error);
  }
});

function emptyMsgEnrollment() {
  setTimeout(() => {
    enrollmentResponseDiv.style.display = "none";
    enrollmentResponseDiv.textContent = "";
  }, 5000);
}

async function editEnrollmentHandler() {
  let selected = document.querySelector("input[name='selected_enrollment_id']:checked");
  if (!selected) return alert("Please select an enrollment first!");

  let newSemId = prompt("Enter new Semester ID:");
  let newYearId = prompt("Enter new Year ID:");
  let newStatus = prompt("Enter new Status:");

  const response = await fetch("Enrollment/updateEnrollment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      enrollment_id: selected.value,
      sem_id: newSemId,
      year_id: newYearId,
      status: newStatus
    })
  });

  const result = await response.json();
  alert(result.message);
  getEnrollment();
}
document.getElementById("editBtnEnrollment").addEventListener("click", editEnrollmentHandler);

async function deleteEnrollmentHandler() {
  let selected = document.querySelector("input[name='selected_enrollment_id']:checked");
  if (!selected) return alert("Please select an enrollment first!");

  let enrollment_id = selected.value;
  if (!confirm("Are you sure you want to delete this enrollment?")) return;

  const response = await fetch("Enrollment/removeEnrollment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ enrollment_id }),
  });

  const result = await response.json();
  alert(result.message);
  getEnrollment();
}
document.getElementById("deleteBtnEnrollment").addEventListener("click", deleteEnrollmentHandler);

//////////////////////////////////////////////////////////////////////////////////////////
// =========================
// SUBJECT CRUD
// =========================

const subjectForm = document.getElementById("subjectForm");
const subjectResponseDiv = document.getElementById("responseSubject");

subjectForm.addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(subjectForm);
  const data = Object.fromEntries(formData.entries());

  try {
    const response = await fetch("Subject/addSubject.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });

    const text = await response.text();
    let result;
    try { result = JSON.parse(text); }
    catch { result = { success: false, message: "Invalid JSON: " + text }; }

    subjectResponseDiv.style.display = "block";
    subjectResponseDiv.textContent = result.message || "No message received";
    subjectResponseDiv.style.color = result.success ? "green" : "red";

    if (result.success) {
      subjectForm.reset();
      getSubject();
      emptyMsgSubject();
    }
  } catch (error) {
    console.error("Error inserting subject:", error);
  }
});

function emptyMsgSubject() {
  setTimeout(() => {
    subjectResponseDiv.style.display = "none";
    subjectResponseDiv.textContent = "";
  }, 5000);
}

document.getElementById("deleteBtnSubject").addEventListener("click", async () => {
    const selected = document.querySelector("input[name='selected_subject_id']:checked");
    if (!selected) return alert("Please select a subject first.");

    const subjectId = selected.value;
    if (!confirm("Are you sure you want to delete this subject?")) return;

    const res = await fetch("Subject/deleteSubject.php", {
        method: "POST",
        headers: { "Content-Type":"application/json" },
        body: JSON.stringify({ subject_id: subjectId })
    });

    const text = await res.text();
    let result;
    try { result = JSON.parse(text); }
    catch { return alert("Server did not return valid JSON. Check console."); }

    alert(result.message);
    if (result.success) getSubject();
});

////////////////////////////////////
// YEAR CRUD
////////////////////////////////////

// Fetch year table
const btnYear = document.getElementById("btn_year");
async function getYear() {
    try {
        const response = await fetch("Year_semester/getYear.php");
        const year_data = await response.json();
        console.log(year_data);

        const table = document.getElementById("Year");
        renderTable(table, year_data, 'No year data found.', "year_id");
    } catch (error) {
        console.error("Error fetching year data:", error);
    }
}
btnYear.addEventListener("click", getYear);

// Insert year
const yearForm = document.getElementById("yearForm");
const yearResponseDiv = document.getElementById("responseYear");

yearForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const formData = new FormData(yearForm);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("Year_semester/insertYear.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });

        const text = await response.text();
        let result;
        try { result = JSON.parse(text); }
        catch { result = { success: false, message: "Invalid JSON: " + text }; }

        yearResponseDiv.style.display = "block";
        yearResponseDiv.textContent = result.message || "No message received";
        yearResponseDiv.style.color = result.success ? "green" : "red";

        if (result.success) {
            yearForm.reset();
            getYear(); // refresh table
            emptyMsgYear();
        }
    } catch (error) {
        console.error("Error inserting year:", error);
    }
});

function emptyMsgYear() {
    setTimeout(() => {
        yearResponseDiv.style.display = "none";
        yearResponseDiv.textContent = "";
    }, 5000);
}

// Edit year
async function editYearHandler() {
    let selected = document.querySelector("input[name='selected_year_id']:checked");
    if (!selected) {
        alert("Please select a year first!");
        return;
    }

    let newName = prompt("Enter new year name:");
    if (!newName) return;

    try {
        const response = await fetch("Year_semester/editYear.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                year_id: selected.value,
                year_name: newName
            })
        });

        const result = await response.json();
        alert(result.message);
        if (result.success) getYear();
    } catch (error) {
        console.error("Error editing year:", error);
    }
}
document.getElementById("editBtnYear").addEventListener("click", editYearHandler);

// Delete year
async function deleteYearHandler() {
    let selected = document.querySelector("input[name='selected_year_id']:checked");
    if (!selected) {
        alert("Please select a year first!");
        return;
    }

    let year_id = selected.value;
    if (!confirm("Are you sure you want to delete this year?")) return;

    try {
        const response = await fetch("Year_semester/deleteYear.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ year_id }),
        });

        const result = await response.json();
        alert(result.message);
        if (result.success) getYear();
    } catch (error) {
        console.error("Error deleting year:", error);
    }
}
document.getElementById("deleteBtnYear").addEventListener("click", deleteYearHandler);


////////////////////////////////////
// PROGRAM CRUD
////////////////////////////////////

const programForm = document.getElementById("programForm");
const programResponseDiv = document.getElementById("programResponse");

// Insert Program
programForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(programForm);
    const data = Object.fromEntries(formData.entries());

    try {
        const response = await fetch("Program/insertProgram.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        });

        const text = await response.text();
        let result;
        try { result = JSON.parse(text); }
        catch { result = { success: false, message: "Invalid JSON: " + text }; }

        programResponseDiv.style.display = "block";
        programResponseDiv.textContent = result.message || "No message received";
        programResponseDiv.style.color = result.success ? "green" : "red";

        if (result.success) {
            programForm.reset();
            getProgram();
            emptyMsgProgram();
        }
    } catch (error) {
        console.error("Error inserting program:", error);
    }
});

function emptyMsgProgram() {
    setTimeout(() => {
        programResponseDiv.style.display = "none";
        programResponseDiv.textContent = "";
    }, 5000);
}
    

// Edit Program
async function editProgramHandler() {
    let selected = document.querySelector("input[name='selected_program_id']:checked");
    if (!selected) return alert("Please select a program first!");

    let newName = prompt("Enter new program name:");
    let newDesc = prompt("Enter new description:");

    if (!newName) return;

    try {
        const response = await fetch("Program/editProgram.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                program_id: selected.value,
                program_name: newName,
                description: newDesc
            })
        });

        const result = await response.json();
        alert(result.message);
        if (result.success) getProgram();
    } catch (error) {
        console.error("Error editing program:", error);
    }
}
document.getElementById("editBtnProgram").addEventListener("click", editProgramHandler);

// Delete Program
async function deleteProgramHandler() {
    let selected = document.querySelector("input[name='selected_program_id']:checked");
    if (!selected) return alert("Please select a program first!");

    let program_id = selected.value;
    if (!confirm("Are you sure you want to delete this program?")) return;

    try {
        const response = await fetch("Program/deleteProgram.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ program_id }),
        });

        const result = await response.json();
        alert(result.message);
        if (result.success) getProgram();
    } catch (error) {
        console.error("Error deleting program:", error);
    }
}
document.getElementById("deleteBtnProgram").addEventListener("click", deleteProgramHandler);
