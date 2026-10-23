# PowerShell script to capture actual screenshots of all website pages
$chrome = "C:\Program Files\Google\Chrome\Application\chrome.exe"
$outDir = "C:\xampp\htdocs\studenthub\website_screenshots"
New-Item -ItemType Directory -Force -Path $outDir | Out-Null

$targets = @(
    @{ name = "shot_home.png"; url = "http://localhost/studenthub/index.php"; w = 1280; h = 800 },
    @{ name = "shot_about.png"; url = "http://localhost/studenthub/about.php"; w = 1280; h = 800 },
    @{ name = "shot_login.png"; url = "http://localhost/studenthub/login.php"; w = 1280; h = 800 },
    @{ name = "shot_register.png"; url = "http://localhost/studenthub/register.php"; w = 1280; h = 950 },
    @{ name = "shot_contact.png"; url = "http://localhost/studenthub/contact.php"; w = 1280; h = 800 },
    @{ name = "shot_view_submissions.png"; url = "http://localhost/studenthub/view-submissions.php"; w = 1280; h = 800 },
    @{ name = "shot_faqs.png"; url = "http://localhost/studenthub/faqs.php"; w = 1280; h = 800 },
    @{ name = "shot_test_connection.png"; url = "http://localhost/studenthub/test_connection.php"; w = 1280; h = 800 },
    @{ name = "shot_students.png"; url = "http://localhost/studenthub/capture_view.php?page=students"; w = 1280; h = 850 },
    @{ name = "shot_student_add.png"; url = "http://localhost/studenthub/capture_view.php?page=student_add"; w = 1280; h = 800 },
    @{ name = "shot_events.png"; url = "http://localhost/studenthub/capture_view.php?page=events"; w = 1280; h = 850 },
    @{ name = "shot_event_add.png"; url = "http://localhost/studenthub/capture_view.php?page=event_add"; w = 1280; h = 850 },
    @{ name = "shot_events_ajax.png"; url = "http://localhost/studenthub/capture_view.php?page=events_ajax"; w = 1280; h = 850 },
    @{ name = "shot_api_events.png"; url = "http://localhost/studenthub/api/events.php"; w = 1280; h = 700 },
    @{ name = "shot_admin_dashboard.png"; url = "http://localhost/studenthub/capture_view.php?page=admin_dashboard"; w = 1280; h = 850 },
    @{ name = "shot_audit_logs.png"; url = "http://localhost/studenthub/capture_view.php?page=audit_logs"; w = 1280; h = 800 },
    @{ name = "shot_users.png"; url = "http://localhost/studenthub/capture_view.php?page=users"; w = 1280; h = 800 },
    @{ name = "shot_profile.png"; url = "http://localhost/studenthub/capture_view.php?page=profile&role=student"; w = 1280; h = 850 },
    @{ name = "shot_feedback.png"; url = "http://localhost/studenthub/feedback.php"; w = 1280; h = 800 },
    @{ name = "shot_wdf_home.png"; url = "http://localhost/studenthub/wdflab/pages/index.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_login.png"; url = "http://localhost/studenthub/wdflab/pages/login.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_profile.png"; url = "http://localhost/studenthub/wdflab/pages/studentprofile.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_coursereg.png"; url = "http://localhost/studenthub/wdflab/pages/coursereg.html"; w = 1280; h = 800 },
    @{ name = "shot_wdf_timetable.png"; url = "http://localhost/studenthub/wdflab/pages/timetable.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_attendance.png"; url = "http://localhost/studenthub/wdflab/pages/attendance.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_examschedule.png"; url = "http://localhost/studenthub/wdflab/pages/examschedule.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_hostels.png"; url = "http://localhost/studenthub/wdflab/pages/hostels.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_notification.png"; url = "http://localhost/studenthub/wdflab/pages/notification.html"; w = 1280; h = 750 },
    @{ name = "shot_wdf_logout.png"; url = "http://localhost/studenthub/wdflab/pages/logout.html"; w = 1280; h = 750 }
)

foreach ($t in $targets) {
    $dest = Join-Path $outDir $t.name
    $cmd = "`"$chrome`" --headless=new --disable-gpu --window-size=$($t.w),$($t.h) --screenshot=`"$dest`" `"$($t.url)`""
    cmd.exe /c "$cmd 2>&1" | Out-Null
    if (Test-Path $dest) {
        $len = (Get-Item $dest).Length
        Write-Output "Captured: $($t.name) ($len bytes)"
    } else {
        Write-Output "FAILED: $($t.name)"
    }
}
