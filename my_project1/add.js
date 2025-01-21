function showSection(sectionId) {
    document.getElementById('students').classList.add('hidden');
    document.getElementById('teachers').classList.add('hidden');
    document.getElementById(sectionId).classList.remove('hidden');
}

// -----------------------------
// Student Management
 



// -----------------------------
// Teacher Management
// -----------------------------
document.addEventListener("DOMContentLoaded", () => {
    // Teacher Form and Table Elements
    const teacherForm = document.getElementById("teacher-form");
    const teacherTableBody = document.querySelector("#teacher-table tbody");

    // Fetch all teachers on page load
    fetchTeachers();

    // Add Teacher
    teacherForm.addEventListener("submit", async (e) => {
        e.preventDefault();

        const teacher = {
            name: document.getElementById("teacher-name").value,
            age: parseInt(document.getElementById("teacher-age").value),
            telephone: document.getElementById("teacher-telephone").value,
            present_days: parseInt(document.getElementById("teacher-present").value),
            absent_days: parseInt(document.getElementById("teacher-absent").value),
        };

        const response = await fetch("teachers.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(teacher),
        });

        const result = await response.json();
        alert(result.message);

        // Refresh the teacher list
        fetchTeachers();

        // Reset the form
        teacherForm.reset();
    });

    // Fetch Teachers
    async function fetchTeachers() {
        const response = await fetch("teachers.php");
        const teachers = await response.json();

        // Clear table before appending new data
        teacherTableBody.innerHTML = "";

        teachers.forEach((teacher) => {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${teacher.name}</td>
                <td>${teacher.age}</td>
                <td>${teacher.telephone}</td>
                <td>${teacher.present_days}</td>
                <td>${teacher.absent_days}</td>
                <td>
                    <button onclick="editTeacher(${teachers.id})"><i class="fas fa-edit"></i> </button>
                    <button onclick="deleteTeacher(${teachers.id})"><i class="fas fa-trash"></i></button>
                </td>
            `;

            teacherTableBody.appendChild(row);
        });
    }

    // Edit Teacher
    window.editTeacher = async (id) => {
        // Fetch the teacher by ID
        const teachers = await getTeacherById(id);
        if (!teachers) {
            alert("Teacher not found!");
            return;
        }
    
        // Fill the form with the existing data
        document.getElementById("teacher-name").value = teachers.name;
        document.getElementById("teacher-age").value = teachers.age;
        document.getElementById("teacher-telephone").value = teachers.telephone;
        document.getElementById("teacher-present").value = teachers.present_days;
        document.getElementById("teacher-absent").value = teachers.absent_days;
    
        // Change the submit button to "Update"
        const submitButton = document.querySelector("#teacher-form button[type='submit']");
        submitButton.textContent = "Update";
    
        // Prevent the default form submission behavior to update teacher
        const originalSubmitHandler = teacherForm.onsubmit;
    
        // Change the form's onsubmit to update the teacher
        teacherForm.onsubmit = async (e) => {
            e.preventDefault();
    
            // Create the updated teacher object
            const updatedTeacher = {
                id: id,
                name: document.getElementById("teacher-name").value,
                age: parseInt(document.getElementById("teacher-age").value),
                telephone: document.getElementById("teacher-telephone").value,
                present_days: parseInt(document.getElementById("teacher-present").value),
                absent_days: parseInt(document.getElementById("teacher-absent").value),
            };
    
            // Send a PUT request to update the teacher
            const response = await fetch("teachers.php", {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(updatedTeacher),
            });
    
            const result = await response.json();
            alert(result.message);
    
            // Refresh the teacher list after updating
            fetchTeachers();
    
            // Reset the form and button text
            teacherForm.reset();
            submitButton.textContent = "Add Teacher";
    
            // Reset the onsubmit handler to the original handler (to handle adding new teacher)
            teacherForm.onsubmit = originalSubmitHandler;
        
    
    
    // Helper function to get teacher by ID
    async function getTeacherById(id) {
        const response = await fetch("teachers.php");
        const teachers = await response.json();
        return teachers.find((teacher) => teacher.id === id);
    }
    
            // Refresh the teacher list
            fetchTeachers();

            // Reset the form and button
            teacherForm.reset();
            submitButton.textContent = "Add Teacher";

            // Reset the form's onsubmit handler
            teacherForm.onsubmit = teacherFormSubmitHandler;
        };
    };

    // Delete Teacher
    window.deleteTeacher = async (id) => {
        if (confirm("Are you sure you want to delete this teacher?")) {
            const response = await fetch("teachers.php", {
                method: "DELETE",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: id }),
            });

            const result = await response.json();
            alert(result.message);

            // Refresh the teacher list
            fetchTeachers();
        }
    };

    // Fetch Teacher by ID (Helper Function)
    async function getTeacherById(id) {
        const response = await fetch("teachers.php");
        const teachers = await response.json();

        return teachers.find((teacher) => teacher.id === id);
    }
});





