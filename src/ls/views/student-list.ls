require! <[
  ./components/html5
  ./components/header
  ./components/setting
]>

require! {m: mithril}

module.exports = (ctrl) ->
  students = ctrl.students
  result = html5(
    ['/css/app.css']
    setting 'Students', students!.map ->
      m "a", (
        config: m.route
        href:   "/students/#{it.student_id}"
      ),
        [m 'button', it.student_name]
  )
