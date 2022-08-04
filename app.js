let tiktokers = []
const min = {}
const max = {}

const statsBody = document.getElementById('stats')
const thead = document.getElementById('thead')

thead.addEventListener('click', e => {
  if (e.target.nodeName == 'BUTTON') {
    const prop = e.target.id.replace('orderBy_', '')
    if (
      !sessionStorage.getItem('order') ||
      sessionStorage.getItem('order') == 'desc'
    ) {
      tiktokers.sort((a, b) => b[prop] - a[prop])
      sessionStorage.setItem('order', 'asc')
    } else {
      tiktokers.sort((a, b) => a[prop] - b[prop])
      sessionStorage.setItem('order', 'desc')
    }
    generateTable()
  }
})

const getMarks = (num, min, max) =>
  num == max ? 'max' : num == min ? 'min' : ''

const getMin = (tiktokers, prop) =>
  Math.min(...tiktokers.map(tiktoker => tiktoker[prop]))
const getMax = (tiktokers, prop) =>
  Math.max(...tiktokers.map(tiktoker => tiktoker[prop]))

const calculateMinMax = () => {
  min.followers = getMin(tiktokers, 'followerCount')
  min.videos = getMin(tiktokers, 'videoCount')
  min.hearts = getMin(tiktokers, 'heartCount')
  min.views = getMin(tiktokers, 'views')
  min.followerPerVideo = getMin(tiktokers, 'followerPerVideo')
  min.heartPerVideo = getMin(tiktokers, 'heartPerVideo')
  min.heartPerFollower = getMin(tiktokers, 'heartPerFollower')
  min.videoStats = getMin(tiktokers, 'videoStats')

  max.followers = getMax(tiktokers, 'followerCount')
  max.videos = getMax(tiktokers, 'videoCount')
  max.hearts = getMax(tiktokers, 'heartCount')
  max.views = getMax(tiktokers, 'views')
  max.followerPerVideo = getMax(tiktokers, 'followerPerVideo')
  max.heartPerVideo = getMax(tiktokers, 'heartPerVideo')
  max.heartPerFollower = getMax(tiktokers, 'heartPerFollower')
  max.videoStats = getMax(tiktokers, 'videoStats')
}

stats.addEventListener('click', e => {
  if (e.target.className.substr(0, 3) == 'num') {
    const tiktoker = e.target.className.substr(4)
    tiktokers = tiktokers.filter(t => t.tiktoker != tiktoker)
    generateTable()
  }
})

const generateTable = () => {
  statsBody.innerHTML = ''
  calculateMinMax()

  tiktokers.forEach((tiktoker, i) => {
    statsBody.innerHTML += `<tr>
    <td class="num ${tiktoker.tiktoker}">${i + 1}</td>

    <td>
      <a href="https://www.tiktok.com/${tiktoker.tiktoker}">
          ${tiktoker.tiktoker}
      </a>
    </td>

    <td class="r ${getMarks(
      tiktoker.followerCount,
      min.followers,
      max.followers
    )}">
      ${Intl.NumberFormat().format(tiktoker.followerCount)}
    </td>

    <td class="r ${getMarks(tiktoker.videoCount, min.videos, max.videos)}">
      ${Intl.NumberFormat().format(tiktoker.videoCount)}
    </td>

    <td class="r ${getMarks(tiktoker.heartCount, min.hearts, max.hearts)}">
      ${Intl.NumberFormat().format(tiktoker.heartCount)}
    </td>

    <td class="b r ${getMarks(
      tiktoker.followerPerVideo,
      min.followerPerVideo,
      max.followerPerVideo
    )}">
      ${Intl.NumberFormat().format(tiktoker.followerPerVideo.toFixed(0))}
      <small>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.followerPerVideo / min.followerPerVideo) * 100).toFixed(
               0
             )
           )} %
        </span>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.followerPerVideo / max.followerPerVideo) * 100).toFixed(
               0
             )
           )} %
        </span>
      </small>
    </td>

    <td class="b r ${getMarks(
      tiktoker.heartPerVideo,
      min.heartPerVideo,
      max.heartPerVideo
    )}">
      ${Intl.NumberFormat().format(tiktoker.heartPerVideo.toFixed(0))}
      <small>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.heartPerVideo / min.heartPerVideo) * 100).toFixed(0)
           )} %
        </span>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.heartPerVideo / max.heartPerVideo) * 100).toFixed(0)
           )} %
        </span>
      </small>
    </td>

    <td class="b r ${getMarks(
      tiktoker.heartPerFollower,
      min.heartPerFollower,
      max.heartPerFollower
    )}">
      ${Intl.NumberFormat().format(tiktoker.heartPerFollower.toFixed(0))}
      <small>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.heartPerFollower / min.heartPerFollower) * 100).toFixed(
               0
             )
           )} %
        </span>
        <span>
           ▴ ${Intl.NumberFormat().format(
             ((tiktoker.heartPerFollower / max.heartPerFollower) * 100).toFixed(
               0
             )
           )} %
        </span>
      </small>
    </td>

    <td class="r ${getMarks(tiktoker.views, min.views, max.views)}">
      ${Intl.NumberFormat().format(tiktoker.views)}
    </td>

    <td class="r ${getMarks(
      tiktoker.videoStats,
      min.videoStats,
      max.videoStats
    )}">
      ${Intl.NumberFormat().format((tiktoker.videoStats * 100).toFixed(2))}%
    </td>
  </tr>`
  })
}

fetch('http://localhost/~rrd/tiktokers/api.php')
  .then(response => response.json())
  .then(data => {
    tiktokers = data.map(tiktoker => ({
      ...tiktoker,
      followerPerVideo: tiktoker.followerCount / tiktoker.videoCount,
      heartPerVideo: tiktoker.heartCount / tiktoker.videoCount,
      heartPerFollower: tiktoker.heartCount / tiktoker.followerCount,
      views: tiktoker.videoStats
        .map(stats => JSON.parse(stats))
        .reduce((acc, cur) => acc + cur.playCount, 0),
      videoStats:
        tiktoker.videoStats
          .map(stats => JSON.parse(stats))
          .map(
            stat =>
              (stat.commentCount + stat.diggCount + stat.shareCount) /
              stat.playCount
          )
          .reduce((acc, cur) => acc + cur, 0) / tiktoker.videoStats.length,
    }))

    generateTable()
  })
