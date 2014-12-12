require! <[
  ./components/html5
  ./components/header
  ./components/setting
]>

require! {m: mithril}

module.exports = (ctrl) ->
  students = ctrl.students! or []
  result = html5(
    ['/css/app.css']
    setting 'Students', students.map ->
      m "a", (
        config: m.route
        href:   "/app/students/#{it.studentId}/quiz"
      ),
        [m 'button', it.studentName]
  )
