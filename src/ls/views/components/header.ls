require! {m: mithril}

titleCase = (s) ->
  s.replace /\w\S*/g, (txt) ->
    txt.charAt 0 .toUpperCase! + txt.substr 1 .toLowerCase!

link = (condition, route) ->
  | condition and route =>
    m "a[href='#{route}']", {config: m.route}, titleCase route.slice (route.lastIndexOf '/') + 1
  | _ => ''

module.exports = ->
  student = m.route.param \id
  quizLink = "/app/students/#{student}/quiz"
  studentDecks = "/app/students/#{student}/decks"
  decksLink = '/app/decks'
  ifNotSelf = (link) -> m.route! isnt link

  result =
    m \header [
      m 'a[href="/"]' [m \h1 m.trust 'L&aelig;re']
      m \nav [
        link (ifNotSelf \/app/students), \/app/students
        link (student and ifNotSelf quizLink), quizLink
        link (student and ifNotSelf studentDecks), studentDecks
        link (not student and ifNotSelf decksLink), decksLink
        m 'a[href="/api/logout"]' 'Log Out'
      ]
    ]
