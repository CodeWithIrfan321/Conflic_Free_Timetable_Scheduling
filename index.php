<!doctype html>
<html>
<head>
  <meta charset='utf-8'>
  <title>University Timetable - Public View</title>
  <link rel="stylesheet" href="style.css">
  <style>
    /* Base Styles */
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 30px;
      background-color: #f4f7f9;
      color: #333;
    }
    .container {
      max-width: 1200px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
      color: #007bff;
      border-bottom: 2px solid #007bff;
      padding-bottom: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    .controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .action-buttons button {
      padding: 10px 15px;
      margin-left: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      cursor: pointer;
      background-color: #e9ecef;
      transition: background-color 0.2s;
    }
    .action-buttons button:hover {
      background-color: #dee2e6;
    }
    
    /* Table Styles (Viewable Timetable) */
    #timetable-view {
      border-collapse: collapse;
      width: 100%;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    #timetable-view th, #timetable-view td {
      border: 1px solid #dee2e6;
      padding: 12px 15px;
      text-align: left;
    }
    #timetable-view thead tr {
      background-color: #007bff;
      color: white;
    }
    #timetable-view tbody tr:nth-child(even) {
      background-color: #f8f9fa;
    }
    .admin-link {
        text-align: right;
        font-size: 14px;
        margin-bottom: 15px;
    }

    /* Print Styles: Hide controls when printing */
    @media print {
        .controls, .admin-link {
            display: none;
        }
        body {
            background: none;
            padding: 0;
        }
        .container {
            box-shadow: none;
            padding: 0;
            margin: 0;
        }
        #timetable-view {
            border: 2px solid #000; /* Ensure borders print clearly */
        }
    }
  </style>
</head>
<body onload="loadTimetable(document.getElementById('viewSemester').value)">
<div class="container">
    <div class="admin-link">
        <a href="admin/login.php">Admin Login</a>
    </div>

    <h2>University Timetable</h2>
    
    <div class="controls">
        <div class="semester-select">
            <label for="viewSemester" style="font-weight: 600;">Select Semester:</label>
            <select id="viewSemester" onchange="loadTimetable(this.value)">
                <option value="">-- Select Semester --</option>
                <option value="2">2nd Semester</option>
                <option value="4">4th Semester</option>
                <option value="6">6th Semester</option>
                <option value="8">8th Semester</option>
            </select>
        </div>
        
        <div class="action-buttons">
            <button onclick="printTimetable()">🖨️ Print Timetable</button>
            <button onclick="downloadPDF()">📄 Download as PDF</button>
            <button onclick="downloadWord()">📄 Download as Word</button>
        </div>
    </div>

    <table id="timetable-view">
        <thead>
            <tr><th>#</th><th>Course Name</th><th>Teacher</th><th>Room</th><th>Day</th><th>Start Time</th><th>End Time</th></tr>
        </thead>
        <tbody>
            <tr><td colspan="7" style="text-align:center;">Please select a semester to view the schedule.</td></tr>
        </tbody>
    </table>
</div>

<script>
// Function to fetch and display the timetable
function loadTimetable(sem) {
    const tableBody = document.querySelector('#timetable-view tbody');
    if (!sem) {
        tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center;">Please select a semester to view the schedule.</td></tr>';
        return;
    }
    
    // Calls the existing backend file to fetch the data
    fetch('backend/get_timetable.php?semester=' + sem)
        .then(response => {
            // Check if the response is valid JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                console.error("Non-JSON response received:", response.statusText);
                tableBody.innerHTML = '<tr><td colspan="7" style="color:red; text-align:center;">Error: Could not retrieve data (Server connection issue).</td></tr>';
                return Promise.reject(new Error("Invalid content type"));
            }
            return response.json();
        })
        .then(data => {
            let rows = '';
            if (data.length === 0) {
                rows = '<tr><td colspan="7" style="text-align:center;">No courses scheduled for this semester.</td></tr>';
            } else {
                data.forEach((row, idx) => {
                    // Truncate time to HH:MM format for cleaner display
                    const startTime = row.start_time.substring(0, 5);
                    const endTime = row.end_time.substring(0, 5);
                    
                    rows += `<tr>
                        <td>${idx + 1}</td>
                        <td>${row.course_name}</td>
                        <td>${row.teacher}</td>
                        <td>${row.room}</td>
                        <td>${row.day}</td>
                        <td>${startTime}</td>
                        <td>${endTime}</td>
                    </tr>`;
                });
            }
            tableBody.innerHTML = rows;
        })
        .catch(e => {
            console.error('Fetch error:', e);
            // If the table is still empty, show a generic error
            if (tableBody.innerHTML.indexOf('Error:') === -1) {
                tableBody.innerHTML = '<tr><td colspan="7" style="color:red; text-align:center;">An unexpected error occurred while loading the data.</td></tr>';
            }
        });
}

// --------------------------------------------------------
// Print and Download Functions
// --------------------------------------------------------

function printTimetable() {
    // Uses the browser's built-in print dialog, leveraging the @media print CSS for cleanliness
    window.print();
}

function downloadPDF() {
    alert("PDF download feature is currently under development. For a printable version, please use the 'Print Timetable' button.");
    // To implement real PDF download, you would typically use a library like jsPDF 
    // or send the table HTML to a server-side script (like PHP with FPDF or similar)
    // for professional PDF generation.
}

function downloadWord() {
    alert("Word (DOC/DOCX) download feature is currently under development. For a printable version, please use the 'Print Timetable' button.");
    // To implement real Word download, you would typically use a library 
    // that handles creating Microsoft Word Open XML files, often done on the server-side.
}

// Load the timetable for the default selected semester on page load
// The function is called in the <body> tag's onload event.

</script>
</body>
</html>