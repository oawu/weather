<div class='container'>
{<{<{ echo render_cell ('demo_cell', 'main_menu', array ()); ?>
  <a class='create' href='{<{<{ echo base_url (array ('events', 'add'));?>'>新增</a>
  {<{<{
    if (isset ($message) && $message) { ?>
      <div class='info'>{<{<{ echo $message;?></div>
  {<{<{
    } ?>
  <table class='table-list'>
    <thead>
      <tr>
        <th width='80'>編號</th>
        <th width='120'>標題</th>
        <th>資訊</th>
        <th width='120'>封面</th>
        <th width='90'>參加人數</th>
        <th width='60'>檢視</th>
        <th width='60'>編輯</th>
        <th width='60'>刪除</th>
      </tr>
    </thead>
    <tbody>
{<{<{ if ($events) {
        foreach ($events as $event) { ?>
          <tr>
            <td>{<{<{ echo $event->id;?></td>
            <td>{<{<{ echo $event->title;?></td>
            <td>{<{<{ echo $event->info;?></td>
            <td>{<{<{ echo img ($event->cover->url ('100w'));?></td>
            <td>{<{<{ echo count ($event->attendees);?></td>
            <td>
              <a href='{<{<{ echo base_url (array ('events', 'show', $event->id));?>'>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 48 48"><path d="M17.995 24c0 3.311 2.689 6 6 6s6-2.689 6-6v-0.050c-0.641 0.65-1.52 1.050-2.5 1.050-1.931 0-3.5-1.57-3.5-3.5 0-1.39 0.819-2.6 1.99-3.16-0.62-0.22-1.29-0.34-1.99-0.34-3.31 0-6 2.69-6 6zM46.655 20.59c-3.72-4.73-12.78-12.59-22.65-12.59-9.88 0-18.949 7.86-22.67 12.59-0.84 1.080-1.3 2.25-1.33 3.41 0.030 1.16 0.49 2.33 1.33 3.41 3.721 4.731 12.78 12.59 22.66 12.59s18.939-7.859 22.66-12.59c0.85-1.080 1.31-2.25 1.34-3.41-0.030-1.16-0.49-2.33-1.34-3.41zM23.995 34c-5.52 0-10-4.48-10-10s4.48-10 10-10 10 4.48 10 10c-0 5.52-4.48 10-10 10z" fill="#000000"></path></svg>
              </a>
            </td>
            <td>
              <a href='{<{<{ echo base_url (array ('events', 'edit', $event->id));?>'>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 48 48"><path d="M5.561 20.207c-0.979 0.979-1.45 2.38-1.689 3.8l-3.812 18.020c-0.54 3.201 2.67 6.421 5.911 5.921l18.041-3.761c1.32-0.239 2.75-0.65 3.73-1.63l19.11-19.131c1.53-1.539 1.53-4.029 0-5.569l-16.69-16.71c-1.54-1.53-4.030-1.53-5.561 0l-19.040 19.060zM23.901 39.437l-9.34 1.791-7.74-7.75 1.779-9.351 13.911-13.93c0.77-0.771 2.010-0.771 2.78 0 0.771 0.77 0.771 2.020 0 2.79l-12.861 12.88c-0.96 0.96-0.96 2.521 0 3.479 0.96 0.961 2.51 0.961 3.47 0l12.871-12.88c0.771-0.77 2.010-0.77 2.78 0 0.771 0.771 0.771 2.011 0 2.78l-12.861 12.88c-0.96 0.96-0.96 2.521 0 3.48 0.96 0.96 2.511 0.96 3.47 0l12.871-12.88c0.761-0.761 2.011-0.761 2.781 0 0.77 0.77 0.77 2.020 0 2.789l-13.911 13.922z" fill="#000000"></path></svg>
              </a>
            </td>
            <td>
              <a href='{<{<{ echo base_url (array ('events', 'destroy', $event->id));?>'>
                &#10006;
              </a>
            </td>
          </tr>
  {<{<{ }
      } else { ?>
        <tr>
          <td colspan='8'>尚未有任何資料。</td>
        </tr>
{<{<{ } ?>

    </tbody>
  </table>
</div>